<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genre = ["Open World", "FPS", "RPG", "Combat", "Puzzle", "MOBA"];
        foreach ($genre as $value) {
            Genre::create([
                'genre_name' => $value
            ]);
        }
    }
}
