<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a default admin user
        User::create([
            'uuid' => '1',
            'name' => 'Vincent',
            'nis' => '12209460',
            'rayon' => 'Wikrama',
            'position' => 'admin',
            'username' => 'vinser',
            'password' => Hash::make('password'),
        ]);

        // Create a few test users
        User::factory()->count(5)->create();
    }
}
