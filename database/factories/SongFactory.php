<?php

namespace Database\Factories;

use App\Models\Album;
use App\Models\Genre;
use App\Models\Song;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Song>
 */
class SongFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "album_id" => Album::factory(),
            "genre_id" => Genre::inRandomOrder()->first()->id, // Busca o primeiro id dos generos cadastrados em ordem aleatória,
            "title" => fake()->words(4, true), // Gera e retorna uma string contendo 4 palavras, o segundo argumento, quando true retorna todas as palavras em uma única string
            "duration_seconds" => fake()->numberBetween(120, 300),
            "is_explicit" => fake()->boolean()
        ];
    }
}
