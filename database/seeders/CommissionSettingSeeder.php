<?php

namespace Database\Seeders;

use App\Models\CommissionSetting;
use Illuminate\Database\Seeder;

class CommissionSettingSeeder extends Seeder
{
    public function run(): void
    {
        CommissionSetting::firstOrCreate(
            ['vendor_id' => null],
            [
                'rate' => 8.50,
                'is_active' => true,
            ]
        );
    }
}
