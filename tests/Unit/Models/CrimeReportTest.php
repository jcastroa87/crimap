<?php

namespace Tests\Unit\Models;

use App\Models\CrimeReport;
use App\Models\CrimeType;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CrimeReportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_crime_report()
    {
        $user = User::factory()->create();
        $crimeType = CrimeType::create([
            'name' => 'Test Crime Type',
            'description' => 'Test description',
            'icon' => 'fa-solid fa-test',
            'color' => '#123456',
            'is_active' => true,
        ]);

        $crimeReport = CrimeReport::create([
            'user_id' => $user->id,
            'crime_type_id' => $crimeType->id,
            'latitude' => 37.7749,
            'longitude' => -122.4194,
            'description' => 'This is a test crime report in San Francisco',
            'occurred_at' => now()->subDay(),
            'status' => 'pending',
            'media_files' => [
                [
                    'path' => 'reports/image1.jpg',
                    'original_name' => 'image1.jpg',
                    'mime_type' => 'image/jpeg',
                ]
            ],
        ]);

        $this->assertDatabaseHas('crime_reports', [
            'user_id' => $user->id,
            'crime_type_id' => $crimeType->id,
            'latitude' => 37.7749,
            'longitude' => -122.4194,
            'description' => 'This is a test crime report in San Francisco',
            'status' => 'pending',
        ]);

        $this->assertEquals($user->id, $crimeReport->user_id);
        $this->assertEquals($crimeType->id, $crimeReport->crime_type_id);
        $this->assertEquals('pending', $crimeReport->status);
        $this->assertIsArray($crimeReport->media_files);
        $this->assertCount(1, $crimeReport->media_files);
    }

    /** @test */
    public function it_can_update_a_crime_report_status()
    {
        $user = User::factory()->create();
        $crimeType = CrimeType::create([
            'name' => 'Test Crime Type',
            'description' => 'Test description',
            'icon' => 'fa-solid fa-test',
            'color' => '#123456',
            'is_active' => true,
        ]);

        $crimeReport = CrimeReport::create([
            'user_id' => $user->id,
            'crime_type_id' => $crimeType->id,
            'latitude' => 37.7749,
            'longitude' => -122.4194,
            'description' => 'This is a test crime report in San Francisco',
            'occurred_at' => now()->subDay(),
            'status' => 'pending',
            'media_files' => [],
        ]);

        $this->assertEquals('pending', $crimeReport->status);

        $crimeReport->update(['status' => 'approved']);

        $this->assertEquals('approved', $crimeReport->fresh()->status);

        $crimeReport->update(['status' => 'rejected']);

        $this->assertEquals('rejected', $crimeReport->fresh()->status);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $crimeType = CrimeType::create([
            'name' => 'Test Crime Type',
            'description' => 'Test description',
            'icon' => 'fa-solid fa-test',
            'color' => '#123456',
            'is_active' => true,
        ]);

        $crimeReport = CrimeReport::create([
            'user_id' => $user->id,
            'crime_type_id' => $crimeType->id,
            'latitude' => 37.7749,
            'longitude' => -122.4194,
            'description' => 'This is a test crime report',
            'occurred_at' => now()->subDay(),
            'status' => 'pending',
            'media_files' => [],
        ]);

        $this->assertInstanceOf(User::class, $crimeReport->user);
        $this->assertEquals($user->id, $crimeReport->user->id);
    }

    /** @test */
    public function it_belongs_to_a_crime_type()
    {
        $user = User::factory()->create();
        $crimeType = CrimeType::create([
            'name' => 'Test Crime Type',
            'description' => 'Test description',
            'icon' => 'fa-solid fa-test',
            'color' => '#123456',
            'is_active' => true,
        ]);

        $crimeReport = CrimeReport::create([
            'user_id' => $user->id,
            'crime_type_id' => $crimeType->id,
            'latitude' => 37.7749,
            'longitude' => -122.4194,
            'description' => 'This is a test crime report',
            'occurred_at' => now()->subDay(),
            'status' => 'pending',
            'media_files' => [],
        ]);

        $this->assertInstanceOf(CrimeType::class, $crimeReport->crimeType);
        $this->assertEquals($crimeType->id, $crimeReport->crimeType->id);
        $this->assertEquals('Test Crime Type', $crimeReport->crimeType->name);
    }
}
