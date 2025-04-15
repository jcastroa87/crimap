<?php

namespace Tests\Unit\Models;

use App\Models\ApiKey;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiKeyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_api_key()
    {
        $user = User::factory()->create();

        $apiKey = ApiKey::create([
            'user_id' => $user->id,
            'name' => 'Test API Key',
            'key' => 'test_' . uniqid(),
            'permissions' => ['read:crime_types', 'read:crime_reports'],
            'expires_at' => now()->addYear(),
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('api_keys', [
            'user_id' => $user->id,
            'name' => 'Test API Key',
            'is_active' => 1,
        ]);

        $this->assertEquals($user->id, $apiKey->user_id);
        $this->assertEquals('Test API Key', $apiKey->name);
        $this->assertTrue($apiKey->is_active);
        $this->assertIsArray($apiKey->permissions);
        $this->assertContains('read:crime_types', $apiKey->permissions);
        $this->assertContains('read:crime_reports', $apiKey->permissions);
    }

    /** @test */
    public function it_can_be_deactivated()
    {
        $user = User::factory()->create();

        $apiKey = ApiKey::create([
            'user_id' => $user->id,
            'name' => 'Active API Key',
            'key' => 'active_' . uniqid(),
            'permissions' => ['read:crime_types'],
            'expires_at' => now()->addYear(),
            'is_active' => true,
        ]);

        $this->assertTrue($apiKey->is_active);

        $apiKey->update(['is_active' => false]);

        $this->assertFalse($apiKey->fresh()->is_active);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();

        $apiKey = ApiKey::create([
            'user_id' => $user->id,
            'name' => 'User API Key',
            'key' => 'user_' . uniqid(),
            'permissions' => ['read:crime_types'],
            'expires_at' => now()->addYear(),
            'is_active' => true,
        ]);

        $this->assertInstanceOf(User::class, $apiKey->user);
        $this->assertEquals($user->id, $apiKey->user->id);
    }

    /** @test */
    public function it_can_update_permissions()
    {
        $user = User::factory()->create();

        $apiKey = ApiKey::create([
            'user_id' => $user->id,
            'name' => 'Limited API Key',
            'key' => 'limited_' . uniqid(),
            'permissions' => ['read:crime_types'],
            'expires_at' => now()->addYear(),
            'is_active' => true,
        ]);

        $this->assertCount(1, $apiKey->permissions);
        $this->assertContains('read:crime_types', $apiKey->permissions);

        $apiKey->update([
            'permissions' => ['read:crime_types', 'read:crime_reports', 'create:crime_reports']
        ]);

        $this->assertCount(3, $apiKey->fresh()->permissions);
        $this->assertContains('create:crime_reports', $apiKey->fresh()->permissions);
    }

    /** @test */
    public function it_can_check_if_expired()
    {
        $user = User::factory()->create();

        $expiredApiKey = ApiKey::create([
            'user_id' => $user->id,
            'name' => 'Expired API Key',
            'key' => 'expired_' . uniqid(),
            'permissions' => ['read:crime_types'],
            'expires_at' => now()->subDay(),
            'is_active' => true,
        ]);

        $validApiKey = ApiKey::create([
            'user_id' => $user->id,
            'name' => 'Valid API Key',
            'key' => 'valid_' . uniqid(),
            'permissions' => ['read:crime_types'],
            'expires_at' => now()->addDay(),
            'is_active' => true,
        ]);

        // Assuming you have an isExpired() method on your ApiKey model
        // If not, you can add one based on this test
        $this->assertTrue($expiredApiKey->expires_at->isPast());
        $this->assertFalse($validApiKey->expires_at->isPast());
    }
}
