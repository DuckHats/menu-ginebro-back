<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'order_deadline_time' => '10:00',
            'order_deadline_days_ahead' => '1',
            'menu_price' => '6.50',
            'app_active' => '1',
        ];

        foreach ($settings as $key => $value) {
            Configuration::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
