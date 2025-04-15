<?php

namespace Database\Seeders;

use App\Models\ApiKey;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ApiKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the test user (or create one if it doesn't exist)
        $user = User::first() ?? User::factory()->create();

        // Create a test API key with full permissions
        ApiKey::create([
            'user_id' => $user->id,
            'name' => 'Test API Key',
            'key' => 'test_' . Str::random(32),
            'permissions' => [
                'read:crime_types',
                'read:crime_reports',
                'create:crime_reports',
                'update:crime_reports',
            ],
            'expires_at' => now()->addYear(),
            'is_active' => true,
            'last_used_at' => null,
        ]);

        // Create a read-only API key
        ApiKey::create([
            'user_id' => $user->id,
            'name' => 'Read-Only API Key',
            'key' => 'readonly_' . Str::random(32),
            'permissions' => [
                'read:crime_types',
                'read:crime_reports',
            ],
            'expires_at' => now()->addMonths(6),
            'is_active' => true,
            'last_used_at' => null,
        ]);
    }
}
