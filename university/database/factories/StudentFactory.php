<?php

namespace Database\Factories;

use App\Models\Degree;
use App\Models\User;
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
            'degree_id' => Degree::factory()->create()->id,
            'user_id' => User::factory()->create()->id
        ];
    }
}
