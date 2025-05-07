<?php

namespace Database\Seeders;

use App\Models\Day;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $days = ['Dilluns', 'Dimarts', 'Dimecres', 'Dijous', 'Divendres'];

        foreach ($days as $day) {
            Day::create(['name' => $day]);
        }
    }
}
