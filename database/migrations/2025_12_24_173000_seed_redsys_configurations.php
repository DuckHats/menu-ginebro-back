<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $configs = [
            ['key' => 'redsys_url', 'value' => 'https://sis-t.redsys.es:25443/sis/realizarPago'],
            ['key' => 'redsys_merchant_code', 'value' => '999008881'],
            ['key' => 'redsys_terminal', 'value' => '1'],
            ['key' => 'redsys_key', 'value' => 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'],
        ];

        foreach ($configs as $config) {
            DB::table('configurations')->insertOrIgnore([
                'key' => $config['key'],
                'value' => $config['value'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('configurations')->whereIn('key', [
            'redsys_url',
            'redsys_merchant_code',
            'redsys_terminal',
            'redsys_key'
        ])->delete();
    }
};
