<?php

namespace Database\Factories;

use App\Models\PasswordReset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PasswordResets>
 */
class PasswordResetFactory extends Factory
{
    protected $model = PasswordReset::class;

    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'token' => rand(100000, 999999),
            'created_at' => now(),
            'expires_at' => now()->addMinutes(15),
        ];
    }
}
