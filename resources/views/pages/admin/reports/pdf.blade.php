<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report: {{ $startDate }} to {{ $endDate }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #1e293b; padding: 40px; }
        h1 { font-size: 22px; margin-bottom: 4px; }
        .subtitle { font-size: 13px; color: #64748b; margin-bottom: 30px; }
        .summary { display: flex; gap: 24px; margin-bottom: 30px; }
        .summary-card { border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px 20px; flex: 1; }
        .summary-card .label { font-size: 11px; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.05em; font-weight: 600; }
        .summary-card .value { font-size: 22px; font-weight: 700; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f8fafc; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; color: #64748b; font-weight: 600; text-align: left; padding: 10px 14px; border-bottom: 2px solid #e2e8f0; }
        td { padding: 10px 14px; border-bottom: 1px solid #f1f5f9; }
        tr:nth-child(even) { background: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; font-size: 11px; color: #94a3b8; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 15px; }
        @media print {
            body { padding: 20px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 24px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-size: 14px; cursor: pointer; font-weight: 600;">
            🖨️ Print / Save as PDF
        </button>
        <button onclick="window.close()" style="padding: 10px 24px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; cursor: pointer; margin-left: 8px;">
            Close
        </button>
    </div>

    <h1>Zentro — Sales Report</h1>
    <p class="subtitle">Period: {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} — {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>

    <div class="summary">
        <div class="summary-card">
            <div class="label">Total Revenue</div>
            <div class="value">${{ number_format($summary['total_revenue'], 2) }}</div>
        </div>
        <div class="summary-card">
            <div class="label">Total Orders</div>
            <div class="value">{{ number_format($summary['total_orders']) }}</div>
        </div>
        <div class="summary-card">
            <div class="label">Avg per Order</div>
            <div class="value">${{ $summary['total_orders'] > 0 ? number_format($summary['total_revenue'] / $summary['total_orders'], 2) : '0.00' }}</div>
        </div>
    </div>

    <h2 style="font-size: 16px; margin-bottom: 10px;">Daily Breakdown</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th class="text-center">Orders</th>
                <th class="text-right">Revenue</th>
            </tr>
        </thead>
        <tbody>
            @forelse($revenues as $row)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}</td>
                    <td class="text-center">{{ $row->total_orders }}</td>
                    <td class="text-right">${{ number_format($row->total_revenue, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center" style="padding: 30px; color: #94a3b8;">No data for this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generated at {{ now()->format('M d, Y H:i') }} — Zentro E-commerce Platform
    </div>
</body>
</html>
