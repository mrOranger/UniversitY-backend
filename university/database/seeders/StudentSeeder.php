<?php

namespace Database\Seeders;

use App\Models\Degree;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void{
        for($i = 0; $i < 200; $i++) {
            Student::factory()->create([
                'degree_id' => Degree::all()->random()->id,
                'user_id' => User::all()->random()->id
            ]);
        }
    }
}
