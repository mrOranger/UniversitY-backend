<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Professor>
 */
class ProfessorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $professor = User::factory()->create(['role' => 'professor']);
        return [
            'level' => $this->faker->randomElement(['researcher', 'associate professor', 'tenured professor']),
            'subject' => $this->faker->randomElement(['ING-INF', 'MAT', 'INF']),
            'user_id' => $professor->id
        ];
    }
}
