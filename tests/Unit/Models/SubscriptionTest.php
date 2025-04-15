<?php

namespace Tests\Unit\Models;

use App\Models\Subscription;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_subscription()
    {
        $user = User::factory()->create();

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_name' => 'Premium',
            'price' => 19.99,
            'started_at' => now(),
            'expires_at' => now()->addMonth(),
            'payment_method' => 'credit_card',
            'payment_id' => 'payment_' . uniqid(),
            'status' => 'active',
            'features' => [
                'max_api_calls' => 10000,
                'real_time_updates' => true,
                'priority_support' => true
            ],
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'plan_name' => 'Premium',
            'price' => 19.99,
            'status' => 'active',
        ]);

        $this->assertEquals($user->id, $subscription->user_id);
        $this->assertEquals('Premium', $subscription->plan_name);
        $this->assertEquals(19.99, $subscription->price);
        $this->assertEquals('active', $subscription->status);
        $this->assertIsArray($subscription->features);
        $this->assertEquals(10000, $subscription->features['max_api_calls']);
        $this->assertTrue($subscription->features['real_time_updates']);
    }

    /** @test */
    public function it_can_update_subscription_status()
    {
        $user = User::factory()->create();

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_name' => 'Basic',
            'price' => 9.99,
            'started_at' => now(),
            'expires_at' => now()->addMonth(),
            'payment_method' => 'paypal',
            'payment_id' => 'payment_' . uniqid(),
            'status' => 'active',
            'features' => [
                'max_api_calls' => 5000,
                'real_time_updates' => false,
            ],
        ]);

        $this->assertEquals('active', $subscription->status);

        $subscription->update(['status' => 'canceled']);

        $this->assertEquals('canceled', $subscription->fresh()->status);

        $subscription->update(['status' => 'expired']);

        $this->assertEquals('expired', $subscription->fresh()->status);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_name' => 'Basic',
            'price' => 9.99,
            'started_at' => now(),
            'expires_at' => now()->addMonth(),
            'payment_method' => 'paypal',
            'payment_id' => 'payment_' . uniqid(),
            'status' => 'active',
            'features' => [
                'max_api_calls' => 5000,
            ],
        ]);

        $this->assertInstanceOf(User::class, $subscription->user);
        $this->assertEquals($user->id, $subscription->user->id);
    }

    /** @test */
    public function it_can_check_if_active()
    {
        $user = User::factory()->create();

        $activeSubscription = Subscription::create([
            'user_id' => $user->id,
            'plan_name' => 'Premium',
            'price' => 19.99,
            'started_at' => now()->subDay(),
            'expires_at' => now()->addMonth(),
            'payment_method' => 'credit_card',
            'payment_id' => 'payment_' . uniqid(),
            'status' => 'active',
            'features' => ['max_api_calls' => 10000],
        ]);

        $expiredSubscription = Subscription::create([
            'user_id' => $user->id,
            'plan_name' => 'Basic',
            'price' => 9.99,
            'started_at' => now()->subMonth()->subDay(),
            'expires_at' => now()->subDay(),
            'payment_method' => 'paypal',
            'payment_id' => 'payment_' . uniqid(),
            'status' => 'expired',
            'features' => ['max_api_calls' => 5000],
        ]);

        // Check active subscription
        $this->assertEquals('active', $activeSubscription->status);
        $this->assertFalse($activeSubscription->expires_at->isPast());

        // Check expired subscription
        $this->assertEquals('expired', $expiredSubscription->status);
        $this->assertTrue($expiredSubscription->expires_at->isPast());
    }
}
