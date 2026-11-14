<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class PlaylistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [ 
            "user_id" => User::factory(),
            "public" => fake()->boolean(),
            "name" => fake()->company(),
            "picture_url" => fake()->imageUrl(300, 300, "abstract")
        ];
    }
}
