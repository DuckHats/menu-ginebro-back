<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Assignar el mes i l'any correctament
        $month = $this->faker->month();
        $year = $this->faker->numberBetween(2024, 2025);

        // Trobar el número del mes
        $monthNumber = date("m", strtotime($month));

        // Generar una data d'inici dins del mes
        $startDate = $this->faker->dateTimeBetween("$year-$monthNumber-01", "$year-$monthNumber-28")->format('Y-m-d');

        // Definir la setmana segons el dia del mes
        $weekNumber = ceil(date('d', strtotime($startDate)) / 7); // Calcula la setmana correcta

        // Assegurar que la data final estigui dins de la mateixa setmana i mes
        $endDate = date("Y-m-d", strtotime("$startDate +6 days"));
        if (date('m', strtotime($endDate)) != $monthNumber) {
            $endDate = date("Y-m-d", strtotime($startDate)); // Ajusta si supera el mes
        }

        return [
            'month' => $month,
            'week' => "Setmana $weekNumber",
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}
