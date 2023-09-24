<?php

namespace Database\Seeders;

use App\Models\Degree;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Student::factory(50)->create([
            'degree_id' => Degree::all()->id
        ]);
    }
}
