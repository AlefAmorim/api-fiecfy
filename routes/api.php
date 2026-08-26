<?php

use App\Http\Controllers\ArtistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MusicaController;
use App\Http\Controllers\PlaylistController;

Route::prefix('musicas')->group(function(){
    Route::get('/', [MusicaController::class, 'index']);
    Route::post('/armazenar', [MusicaController::class, 'store']);
});

Route::prefix('playlists')->group(function(){
    Route::get('/', [PlaylistController::class, 'index']);
    Route::post('/criar', [PlaylistController::class, 'store']);
    Route::delete('/delete/{id}', [PlaylistController::class, 'destroy']);
});

Route::prefix('artists')->group(function(){
    Route::get('/', [ArtistController::class, 'index']);
    Route::get('/search-artist/{id}', [ArtistController::class, 'show']);
    Route::patch('/update-artist/{id}', [ArtistController::class, 'update']);
    Route::delete('/delete-artist/{id}', [ArtistController::class, 'destroy']);
    Route::post("/create-artist", [ArtistController::class, 'store']);
});