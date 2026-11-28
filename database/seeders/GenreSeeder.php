<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    private array $genres = ["Pop", "Rock", "R&B", "Samba", "Pagode", "Countryside", "Funk", "Eletronic", "Jazz", "Podcasts"];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $default = [];
        foreach ($this->genres as $genre) {
            array_push($default, [
                "name" => $genre,
                "description" => fake()->paragraph(),
                "cover_image" => fake()->imageUrl(300, 300, "nature"),
                "hex_cover_bg" => fake()->hexColor()
            ]);
        }

        foreach ($default as $genre) {
            Genre::firstOrCreate(["name" => $genre["name"]], $genre); // Verifica se já existe algum genêro com o nome, se não cria-o
        }
    }
}
