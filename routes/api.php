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

/*
 * 
 * Rotas para autenticação
 * 
 */
Route::prefix("auth")->group(function () {
    Route::post("/register", [AuthController::class, "register"]);
    Route::post("/login", [AuthController::class, "login"]);
    Route::get("/me", [AuthController::class, "me"])->middleware("auth:sanctum");
});

/*
 * 
 * Rotas para manipulação dos usuários
 * 
 */
Route::prefix("user")->group(function () {
    Route::post("/upload", [UserController::class, "upload"]);
    Route::post("/create-user", [UserController::class, "store"]);
    Route::get("/show-users", [UserController::class, "index"]);
})->middleware("auth:sanctum");

/*
 * 
 * Rotas para manipulação das músicas
 * 
 */
Route::prefix('songs')->group(function () {
    // Rotas Públicas 
    Route::get('/list-songs', [SongsController::class, 'index']);

    // Rotas com autenticação
    Route::middleware("auth:sanctum")->group(function () {
        Route::post("/create-song", [SongsController::class, 'store']);
        Route::patch('/update-song/{id}', [SongsController::class, 'update']);
        Route::delete('/delete-song/{id}', [SongsController::class, 'destroy']);
        Route::patch('/inactivate-song/{id}', [SongsController::class, 'inactivate']);
        Route::patch('/activate-song/{id}', [SongsController::class, 'activate']);
    });
});

/*
 * 
 * Rotas para manipulação das playlists
 * 
 */
Route::prefix('playlists')->group(function () {
    // Rotas Públicas 
    Route::get('/list-public-playlists', [PlaylistController::class, 'listPublicPlaylists']);

    // Rotas com autenticação
    Route::middleware("auth:sanctum")->group(function () {
        Route::get('/list-playlists', [PlaylistController::class, 'index']);
        Route::get('/list-playlists-user', [PlaylistController::class, 'listPlaylistsUser']);
        Route::post('/create-playlist', [PlaylistController::class, 'store']);
        Route::post('/add-song/{id}', [PlaylistController::class, 'addSong']);
        Route::patch('/update-song/{id}', [PlaylistController::class, 'update']);
        Route::patch('/inactivate-playlist/{id}', [PlaylistController::class, 'inactivate']);
        Route::patch('/activate-playlist/{id}', [PlaylistController::class, 'activate']);
        Route::delete('/delete/{id}', [PlaylistController::class, 'destroy']);
    });
});

/*
 * 
 * Rotas para manipulação dos gêneros musicais
 * 
 */
Route::prefix('genres')->group(function () {
    // Rotas Públicas 
    Route::get('/list-genres', [GenreController::class, 'index']);

    // Rotas com autenticação
    Route::middleware("auth:sanctum")->group(function () {
        Route::post('/create-genre', [GenreController::class, 'store']);
        Route::patch('/update-song/{id}', [GenreController::class, 'update']);
        Route::patch('/inactivate-playlist/{id}', [GenreController::class, 'inactivate']);
        Route::patch('/activate-playlist/{id}', [GenreController::class, 'activate']);
        Route::delete('/delete/{id}', [GenreController::class, 'destroy']);
    });
});

/*
 * 
 * Rotas para manipulação dos artistas
 * 
 */
Route::prefix('artists')->group(function () {
    // Rotas Públicas 
    Route::get('/list-artists', [ArtistController::class, 'index']);
    Route::get('/search-artist/{id}', [ArtistController::class, 'show']);

    // Rotas com autenticação
    Route::middleware("auth:sanctum")->group(function () {
        Route::post("/create-artist", [ArtistController::class, 'store']);
        Route::patch('/update-artist/{id}', [ArtistController::class, 'update']);
        Route::patch('/inactivate-artist/{id}', [ArtistController::class, 'inactivate']);
        Route::patch('/activate-artist/{id}', [ArtistController::class, 'activate']);
        Route::delete('/delete-artist/{id}', [ArtistController::class, 'destroy']);
    });
});

/*
 * 
 * Rotas para manipulação dos albums
 * 
 */
Route::prefix('albums')->group(function () {
    // Rotas com públicas
    Route::get('/list-albums', [AlbumController::class, 'index']);
    Route::get('/list-album/{id}', [AlbumController::class, 'show']);

    // Rotas com autenticação
    Route::middleware("auth:sanctum")->group(function () {
        Route::post('/create-album', [AlbumController::class, 'store']);
        Route::patch('/update-album/{id}', [AlbumController::class, 'update']);
        Route::patch('/inactivate-album/{id}', [AlbumController::class, 'inactivate']);
        Route::patch('/activate-album/{id}', [AlbumController::class, 'activate']);
        Route::delete('/delete-album/{id}', [AlbumController::class, 'destroy']);
    });
});

/*
 * 
 * Rota para teste da API
 * 
 */
Route::get("/teste-api", function () {
    return response()->json([
        "message" => "A API está rodando!🧑‍💻"
    ], 200);
});

/*
 * 
 * Rotas para teste dos tokens
 * 
 */

// Gera token com todas as permissões/habilidades
Route::get("/gerar-token", function (Request $request) {
    $tkn = User::findOrFail(1)->createToken("meu_tkn");

    return response()->json([
        "user" => $request->user(),
        "tkn" => $tkn->plainTextToken
    ], 200);
});

//Gera token com a permissão/habilidade de usuário
Route::get("/gerar-token-user", function (Request $request) {
    $tkn = User::findOrFail(1)->createToken("meu_tkn", ["user"]);

    return response()->json([
        "user" => $request->user(),
        "tkn" => $tkn->plainTextToken
    ], 200);
});

//Gera token com a permissão/habilidade de admin
Route::get("/gerar-token-admin", function (Request $request) {
    $tkn = User::findOrFail(1)->createToken("meu_tkn", ["admin"]);

    return response()->json([
        "user" => $request->user(),
        "tkn" => $tkn->plainTextToken
    ], 200);
});