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

        $descriptions = [
            "El gluten és un conjunt de proteïnes presents en cereals com el blat, l’ordi, el sègol i els seus derivats.",
            "La lactosa és un sucre natural present a la llet i als productes lactis.",
            "Els fruits secs inclouen ametlles, avellanes, nous, anacards, pacanes, festucs i nous del Brasil.",
            "L’ou és un al·lergen comú present en ous de gallina i en molts productes elaborats.",
            "El peix inclou totes les espècies de peix i productes que en continguin derivats.",
            "El marisc inclou crustacis com gambes, llagostins, crancs i escamarlans.",
            "La soja és un llegum utilitzat en molts aliments i productes processats.",
            "Els cacauets són un tipus de llegum que pot provocar reaccions al·lèrgiques greus.",
            "L’api pot trobar-se tant en forma fresca com en sopes, brous i productes elaborats.",
            "La mostassa inclou les llavors de mostassa i els productes que en continguin derivats.",
            "El sèsam inclou llavors de sèsam i productes elaborats amb aquestes.",
            "Els sulfits són conservants utilitzats en vins, fruits secs i altres aliments processats.",
            "Els tramussos són llegums utilitzats en alguns productes alimentaris, especialment en farines.",
            "Els mol·luscs inclouen musclos, cloïsses, ostres, calamars i sípies.",
        ];


        foreach ($allergies as $index => $allergy) {
            Allergy::firstOrCreate(['name' => $allergy], ['description' => $descriptions[$index]]);
        }
    }
}
