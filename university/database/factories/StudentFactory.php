<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bachelor_final_mark' => $this->faker->numberBetween(66, 110),
            'master_final_mark' => $this->faker->numberBetween(66, 110),
            'phd_final_mark' => $this->faker->numberBetween(66, 110),
            'outside_prescribed_time' => $this->faker->boolean(),
            'user' => $this->faker->randomNumber(),
            'course' => $this->faker->randomNumber(),
        ];
    }
}
