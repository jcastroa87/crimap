<?php

namespace Tests\Unit\Models;

use App\Models\CrimeType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CrimeTypeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_crime_type()
    {
        $crimeType = CrimeType::create([
            'name' => 'Test Crime Type',
            'description' => 'This is a test crime type',
            'icon' => 'fa-solid fa-test',
            'color' => '#123456',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('crime_types', [
            'name' => 'Test Crime Type',
            'description' => 'This is a test crime type',
        ]);

        $this->assertEquals('Test Crime Type', $crimeType->name);
        $this->assertEquals('This is a test crime type', $crimeType->description);
        $this->assertEquals('fa-solid fa-test', $crimeType->icon);
        $this->assertEquals('#123456', $crimeType->color);
        $this->assertTrue($crimeType->is_active);
    }

    /** @test */
    public function it_can_update_a_crime_type()
    {
        $crimeType = CrimeType::create([
            'name' => 'Original Crime Type',
            'description' => 'This is the original description',
            'icon' => 'fa-solid fa-original',
            'color' => '#654321',
            'is_active' => true,
        ]);

        $crimeType->update([
            'name' => 'Updated Crime Type',
            'description' => 'This is the updated description',
            'is_active' => false,
        ]);

        $this->assertDatabaseHas('crime_types', [
            'name' => 'Updated Crime Type',
            'description' => 'This is the updated description',
            'is_active' => 0,
        ]);

        $this->assertEquals('Updated Crime Type', $crimeType->fresh()->name);
        $this->assertFalse($crimeType->fresh()->is_active);
    }

    /** @test */
    public function it_can_be_deactivated()
    {
        $crimeType = CrimeType::create([
            'name' => 'Active Crime Type',
            'description' => 'This is an active crime type',
            'icon' => 'fa-solid fa-active',
            'color' => '#111111',
            'is_active' => true,
        ]);

        $this->assertTrue($crimeType->is_active);

        $crimeType->update(['is_active' => false]);

        $this->assertFalse($crimeType->fresh()->is_active);
    }
}
