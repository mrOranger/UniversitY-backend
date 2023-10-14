<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->text(50),
            'sector' => $this->faker->text(10),
            'starting_date' => $this->faker->date(),
            'ending_date' => $this->faker->date(),
            'cfu' => $this->faker->randomNumber(1),
            'professor_id' => Teacher::factory()->create()->id,
        ];
    }
}
