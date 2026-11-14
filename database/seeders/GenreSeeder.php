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
        $default = [
            [
                "name" => "Pop",
                "description" => fake()->paragraph(),
                "cover_image" => fake()->imageUrl(300, 300, "nature"),
            ],
            [
                "name" => "Rock",
                "description" => fake()->paragraph(),
                "cover_image" => fake()->imageUrl(300, 300, "nature"),
            ],
            [
                "name" => "R&B",
                "description" => fake()->paragraph(),
                "cover_image" => fake()->imageUrl(300, 300, "nature"),
            ],
            [
                "name" => "Samba",
                "description" => fake()->paragraph(),
                "cover_image" => fake()->imageUrl(300, 300, "nature"),
            ],
            [
                "name" => "Pagode",
                "description" => fake()->paragraph(),
                "cover_image" => fake()->imageUrl(300, 300, "nature"),
            ],
            [
                "name" => "Sertanejo",
                "description" => fake()->paragraph(),
                "cover_image" => fake()->imageUrl(300, 300, "nature"),
            ],
            [
                "name" => "Funk",
                "description" => fake()->paragraph(),
                "cover_image" => fake()->imageUrl(300, 300, "nature"),
            ],
            [
                "name" => "Eletrônica",
                "description" => fake()->paragraph(),
                "cover_image" => fake()->imageUrl(300, 300, "nature"),
            ],
            [
                "name" => "Podcast",
                "description" => fake()->paragraph(),
                "cover_image" => fake()->imageUrl(300, 300, "nature"),
            ]
        ];
        
        foreach ($default as $genre) {
            Genre::firstOrCreate(["name" => $genre["name"]], $genre); // Verifica se já existe algum genêro com o nome, se não cria-o
        }
    }
}
