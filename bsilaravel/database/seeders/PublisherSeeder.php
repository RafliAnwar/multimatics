<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $publisher = ["Riot", "Blizzard", "Square Enix", "Activision", "Electronic Arts", "CD Projekt"];
        foreach ($publisher as $value) {
            Publisher::create([
                'publisher_name' => $value
            ]);
        }
    }
}
