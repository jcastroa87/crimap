<?php

namespace Database\Seeders;

use App\Models\CrimeReport;
use App\Models\CrimeType;
use App\Models\User;
use Illuminate\Database\Seeder;

class CrimeReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing crime types
        $crimeTypes = CrimeType::all();

        // Get the test user (or create one if it doesn't exist)
        $user = User::first() ?? User::factory()->create();

        // San Francisco area coordinates (approximately)
        $sfCenter = [
            'lat' => 37.7749,
            'lng' => -122.4194
        ];

        // Create 50 random crime reports in the San Francisco area
        for ($i = 0; $i < 50; $i++) {
            // Random location within ~3km of center
            $lat = $sfCenter['lat'] + (mt_rand(-300, 300) / 100 * 0.01);
            $lng = $sfCenter['lng'] + (mt_rand(-300, 300) / 100 * 0.01);

            // Random crime type
            $crimeType = $crimeTypes->random();

            // Random date within the last 90 days
            $date = now()->subDays(mt_rand(0, 90))->subHours(mt_rand(0, 24));

            CrimeReport::create([
                'user_id' => $user->id,
                'crime_type_id' => $crimeType->id,
                'latitude' => $lat,
                'longitude' => $lng,
                'description' => 'This is a sample ' . strtolower($crimeType->name) . ' report generated for testing purposes. The incident occurred near latitude ' . $lat . ' and longitude ' . $lng . '.',
                'occurred_at' => $date,
                'status' => ['pending', 'approved', 'rejected'][mt_rand(0, 2)],
                'media_files' => [],
            ]);
        }
    }
}
