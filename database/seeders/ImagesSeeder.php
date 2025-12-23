<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startDate = Carbon::now()->startOfDay();

        for ($i = 0; $i < 10; $i++) {

            $endDate = $startDate->copy()->addMonth();

            Image::factory()->create([
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'month' => $startDate->monthName,
                'year' => $startDate->year,
            ]);

            $startDate = $endDate;
        }
    }
}
