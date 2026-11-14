<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Artist; 
use  App\Models\Album;
use App\Models\Playlist;
use App\Models\Song;
use Database\Factories\PlaylistFactory;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::factory(20)->create();
        $this->call(GenreSeeder::class); // Chama um seeder específicando a classe do seeder
        Artist::factory(10)->create();
        Album::factory(20)->create();
        Song::factory(100)->create();
        Playlist::factory(10)->create();

        User::factory()->create([
            'name' => 'Dev User',
            'email' => 'dev@dev.com',
        ]);
    }
}
