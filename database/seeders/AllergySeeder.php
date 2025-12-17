<?php

namespace Database\Seeders;

use App\Models\Allergy;
use Illuminate\Database\Seeder;

class AllergySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allergies = [
            "Gluten",
            "Lactosa",
            "Fruits secs",
            "Ou",
            "Peix",
            "Marisc",
            "Soja",
            "Cacauets",
            "Api",
            "Mostassa",
            "Sèsam",
            "Sulfits",
            "Tramussos",
            "Mol·luscs",
        ];

        foreach ($allergies as $allergy) {
            Allergy::firstOrCreate(['name' => $allergy]);
        }
    }
}
