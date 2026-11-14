<?php

namespace Database\Factories;

use App\Models\Artist;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Artist>
 */
class ArtistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => fake()->name(), 
            "monthly_listeners" => fake()->randomNumber(), 
            "genre_id" => Genre::inRandomOrder()->first()->id, // Busca o primeiro id dos generos cadastrados em ordem aleatória
            "profile_pic_url" => fake()->imageUrl(300, 300, "people")
        ];
    }
}
