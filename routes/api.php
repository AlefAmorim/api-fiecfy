<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\SongsController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;

Route::prefix("auth")->group(function () {
    Route::post("/register", [AuthController::class, "register"]);
    Route::post("/login", [AuthController::class, "login"]);
});

Route::get("/gerar-token", function (Request $request) {
    $tkn = User::findOrFail(1)->createToken("meu_tkn");

    return response()->json([
        "user" => $request->user(),
        "tkn" => $tkn
    ], 200);
});

Route::prefix('artists')->group(function(){
    Route::post("/create-artist", [ArtistController::class, 'store']);
    Route::get('/list-artists', [ArtistController::class, 'index']);
    Route::get('/search-artist/{id}', [ArtistController::class, 'show']);
    Route::patch('/update-artist/{id}', [ArtistController::class, 'update']);
    Route::delete('/delete-artist/{id}', [ArtistController::class, 'destroy']);
});

Route::prefix('playlists')->group(function(){
    Route::post('/create-playlist', [PlaylistController::class, 'store']);
    Route::get('/list-playlists', [PlaylistController::class, 'index'])->middleware("auth:sanctum");
    Route::post('/add-song/{id}', [PlaylistController::class, 'addSong']);
    Route::delete('/delete/{id}', [PlaylistController::class, 'destroy']);
});
    
Route::middleware("auth:sanctum")->prefix('songs')->group(function(){
    Route::post('/create-song', [SongsController::class, 'store']);
    Route::get('/list-songs', [SongsController::class, 'index']);
});

Route::prefix('albums')->group(function () {
    Route::post('/create-album', [AlbumController::class, 'store']);
    Route::get('/list-albums', [AlbumController::class, 'index']);
    Route::get('/list-album/{id}', [AlbumController::class, 'show']);
    Route::patch('/update-album/{id}', [AlbumController::class, 'update']);
    Route::delete('/delete-album/{id}', [AlbumController::class, 'destroy']);
});

Route::prefix('genres')->group(function() {
    Route::post("/create-genre", [GenreController::class, 'store']);
});