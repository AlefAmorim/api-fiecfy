<?php

namespace Database\Factories;

use App\Models\Album;
use App\Models\Artist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Album>
 */
class AlbumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title" => fake()->sentence(3), // Gera uma frase com 3 palavras
            "artist_id" => Artist::factory(), // Gera um artista 
            "release_year" => fake()->year(), //Retorna um ano aleatório 
            "cover_image" => fake()->imageUrl(300, 300, "nightlife"), //Retorna um ano aleatório 
        ];
    }
}
