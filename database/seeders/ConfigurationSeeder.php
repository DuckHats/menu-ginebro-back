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
            'redsys_url' => 'https://sis-t.redsys.es:25443/sis/realizarPago',
            'redsys_merchant_code' => '999008881',
            'redsys_terminal' => '1',
            'redsys_key' => 'sq7HjrUOBfKmC576ILgskD5srU870gJ7',
            'taper_price' => '1.00',
            'half_menu_first_price' => '4.50',
            'half_menu_second_price' => '4.50',
        ];

        foreach ($settings as $key => $value) {
            Configuration::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
