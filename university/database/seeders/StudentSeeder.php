<?php

namespace Database\Seeders;

use App\Models\Course;
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
        Student::factory(100)->create();
        foreach (Course::all() as $course){
            $students = Student::all()->take(rand(1,10))->pluck('id');
            $course->students()->attach($students);
        }
    }
}
