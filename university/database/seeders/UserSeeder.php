<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->createQuietly([
            'role' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('ThisIsAValidPassword!'),
        ]);
        User::factory(50)->createQuietly();
    }
}
