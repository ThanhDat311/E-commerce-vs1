<?php

namespace App\Console\Commands;

use App\Models\OrderItem;
use App\Models\Payout;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessVendorPayouts extends Command
{
    protected $signature = 'app:process-vendor-payouts
                            {--month= : Month to process (default: previous month, format: YYYY-MM)}
                            {--commission=10 : Commission percentage to deduct}
                            {--export : Export payout data as a CSV file to storage/app/payouts/}';

    protected $description = 'Calculate and create monthly payout records for all vendors';

    public function handle(): int
    {
        $commissionRate = (float) $this->option('commission');

        // Determine the payout period (default: previous month)
        if ($month = $this->option('month')) {
            $periodStart = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } else {
            $periodStart = now()->subMonth()->startOfMonth();
        }
        $periodEnd = $periodStart->copy()->endOfMonth();

        $this->info("Processing payouts for period: {$periodStart->toDateString()} to {$periodEnd->toDateString()}");

        // Get all vendors (role_id = 4)
        $vendors = User::where('role_id', 4)->get();

        if ($vendors->isEmpty()) {
            $this->warn('No vendors found.');

            return self::SUCCESS;
        }

        $payoutsCreated = 0;

        foreach ($vendors as $vendor) {
            // Calculate total revenue from delivered orders for this vendor in the period
            $totalRevenue = OrderItem::whereHas('order', function ($query) use ($periodStart, $periodEnd) {
                $query->where('order_status', 'delivered')
                    ->whereBetween('created_at', [$periodStart, $periodEnd]);
            })
                ->whereHas('product', function ($query) use ($vendor) {
                    $query->withoutGlobalScopes()->where('vendor_id', $vendor->id);
                })
                ->sum(DB::raw('price * quantity'));

            if ($totalRevenue <= 0) {
                continue;
            }

            $commissionAmount = $totalRevenue * ($commissionRate / 100);
            $payoutAmount = $totalRevenue - $commissionAmount;

            // Check if payout already exists for this vendor + period
            $exists = Payout::where('vendor_id', $vendor->id)
                ->where('period_start', $periodStart->toDateString())
                ->where('period_end', $periodEnd->toDateString())
                ->exists();

            if ($exists) {
                $this->line("  Skipping vendor #{$vendor->id} ({$vendor->name}) — payout already exists.");

                continue;
            }

            Payout::create([
                'vendor_id' => $vendor->id,
                'amount' => $payoutAmount,
                'commission_rate' => $commissionRate,
                'commission_amount' => $commissionAmount,
                'status' => 'pending',
                'period_start' => $periodStart->toDateString(),
                'period_end' => $periodEnd->toDateString(),
                'notes' => "Auto-generated. Gross: {$totalRevenue}, Commission: {$commissionAmount}",
            ]);

            $payoutsCreated++;
            $this->line("  Vendor #{$vendor->id} ({$vendor->name}): Gross {$totalRevenue} → Payout {$payoutAmount}");
        }

        $this->info("Done! {$payoutsCreated} payout(s) created.");

        if ($this->option('export')) {
            $this->exportCsv($periodStart, $periodEnd);
        }

        return self::SUCCESS;
    }

    private function exportCsv(\Carbon\Carbon $periodStart, \Carbon\Carbon $periodEnd): void
    {
        $payouts = Payout::with('vendor')
            ->where('period_start', $periodStart->toDateString())
            ->where('period_end', $periodEnd->toDateString())
            ->get();

        if ($payouts->isEmpty()) {
            $this->warn('No payouts found for CSV export.');

            return;
        }

        $filename = 'payouts_'.$periodStart->format('Y-m').'_'.now()->format('YmdHis').'.csv';
        $directory = storage_path('app/payouts');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $path = $directory.'/'.$filename;
        $handle = fopen($path, 'w');

        // Header row
        fputcsv($handle, [
            'Vendor ID',
            'Vendor Name',
            'Vendor Email',
            'Period Start',
            'Period End',
            'Gross Revenue',
            'Commission Rate (%)',
            'Commission Amount',
            'Net Payout',
            'Status',
        ]);

        foreach ($payouts as $payout) {
            fputcsv($handle, [
                $payout->vendor_id,
                $payout->vendor->name ?? 'N/A',
                $payout->vendor->email ?? 'N/A',
                $payout->period_start->toDateString(),
                $payout->period_end->toDateString(),
                number_format((float) $payout->amount + (float) $payout->commission_amount, 2, '.', ''),
                (float) $payout->commission_rate,
                number_format((float) $payout->commission_amount, 2, '.', ''),
                number_format((float) $payout->amount, 2, '.', ''),
                $payout->status,
            ]);
        }

        fclose($handle);

        $this->info("CSV exported to: {$path}");
    }
}
