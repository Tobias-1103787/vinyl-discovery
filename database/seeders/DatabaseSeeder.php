<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User admin',
            'email' => 'test@example.com',
            'admin' => true,
            'password' => Hash::make('password')
        ]);
        User::factory()->create([
            'name' => 'Test User normal',
            'email' => 'test2@example.com',
            'admin' => false,
            'password' => Hash::make('password')
        ]);
    }
}
