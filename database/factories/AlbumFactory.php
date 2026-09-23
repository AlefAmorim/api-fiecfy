<?php

namespace Database\Factories;

use App\Models\Album;
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
            "artist_id" => \App\Models\Album::factory(), // Gera um artista 
            "release_year" => fake()->year(), //Retorna um ano aleatório 
        ];
    }
}
