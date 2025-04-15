<?php

namespace Database\Seeders;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the test user (or create one if it doesn't exist)
        $user = User::first() ?? User::factory()->create();

        // Create subscription plans
        $subscriptionPlans = [
            [
                'user_id' => $user->id,
                'name' => 'Basic Plan',
                'description' => 'Access to basic crime data',
                'frequency' => 'monthly',
                'price' => 9.99,
                'features' => [
                    'Access to basic crime data',
                    'Limited API calls (1000/month)',
                    'Email alerts for your area'
                ],
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addYear(),
            ],
            [
                'user_id' => $user->id,
                'name' => 'Premium Plan',
                'description' => 'Full access to all crime data',
                'frequency' => 'annual',
                'price' => 99.99,
                'features' => [
                    'Access to all crime data',
                    'Unlimited API calls',
                    'Real-time alerts for custom areas',
                    'Advanced analytics and reporting',
                    'Data export capabilities'
                ],
                'status' => 'pending',
                'starts_at' => now()->addDays(5),
                'ends_at' => now()->addDays(5)->addYear(),
            ],
            [
                'user_id' => $user->id,
                'name' => 'Enterprise Plan',
                'description' => 'Custom integration and dedicated support',
                'frequency' => 'annual',
                'price' => 499.99,
                'features' => [
                    'Full data access with historical archive',
                    'Unlimited API calls with higher rate limits',
                    'Custom integrations with your systems',
                    'Dedicated account manager',
                    'White-labeled reports and dashboards',
                    '24/7 priority support'
                ],
                'status' => 'cancelled',
                'starts_at' => now()->subMonths(3),
                'ends_at' => now()->subDays(5),
            ]
        ];

        foreach ($subscriptionPlans as $plan) {
            Subscription::create($plan);
        }
    }
}
