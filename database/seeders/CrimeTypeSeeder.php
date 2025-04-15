<?php

namespace Database\Seeders;

use App\Models\CrimeType;
use Illuminate\Database\Seeder;

class CrimeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $crimeTypes = [
            [
                'name' => 'Theft',
                'description' => 'Property stolen from a person or premises',
                'icon' => 'fa-solid fa-bag-shopping',
                'color' => '#e74c3c',
                'is_active' => true,
            ],
            [
                'name' => 'Assault',
                'description' => 'Physical attack against a person',
                'icon' => 'fa-solid fa-hand-fist',
                'color' => '#c0392b',
                'is_active' => true,
            ],
            [
                'name' => 'Burglary',
                'description' => 'Illegal entry into a building with intent to commit a crime',
                'icon' => 'fa-solid fa-house-chimney-crack',
                'color' => '#9b59b6',
                'is_active' => true,
            ],
            [
                'name' => 'Vandalism',
                'description' => 'Deliberate destruction or damage to property',
                'icon' => 'fa-solid fa-hammer',
                'color' => '#3498db',
                'is_active' => true,
            ],
            [
                'name' => 'Drug Offense',
                'description' => 'Incidents related to illegal substances',
                'icon' => 'fa-solid fa-pills',
                'color' => '#2ecc71',
                'is_active' => true,
            ],
            [
                'name' => 'Traffic Incident',
                'description' => 'Accidents or violations on roadways',
                'icon' => 'fa-solid fa-car-burst',
                'color' => '#f1c40f',
                'is_active' => true,
            ],
            [
                'name' => 'Fraud',
                'description' => 'Deception for unlawful gain',
                'icon' => 'fa-solid fa-credit-card',
                'color' => '#e67e22',
                'is_active' => true,
            ],
            [
                'name' => 'Other',
                'description' => 'Other types of crime not listed',
                'icon' => 'fa-solid fa-question',
                'color' => '#7f8c8d',
                'is_active' => true,
            ],
        ];

        foreach ($crimeTypes as $crimeType) {
            CrimeType::create($crimeType);
        }
    }
}
