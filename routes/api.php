<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MusicaController;

Route::prefix('musicas')->group(function(){
    Route::get('/', [MusicaController::class, 'index']);
    Route::post('/armazenar', [MusicaController::class, 'store']);
});