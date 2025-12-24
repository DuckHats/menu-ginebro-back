<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    public function definition(): array
    {
        $imagePath = config('app.url') . '/img/menu/menu_example.png';

        // Data d'inici aleatòria
        $startDate = Carbon::parse($this->faker->date());

        // Data final = mateix dia, mes següent
        $endDate = $startDate->copy()->addMonth();

        return [
            'path' => $imagePath,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'month' => $startDate->monthName,
            'year' => $startDate->year,
        ];
    }
}
