<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@crimap.com',
            'password' => Hash::make('password'), // Change in production!
        ]);

        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'), // Change in production!
        ]);

        // Call other seeders
        $this->call([
            CrimeTypeSeeder::class,
            CrimeReportSeeder::class,
            ApiKeySeeder::class,
            SubscriptionSeeder::class,
        ]);
    }
}
