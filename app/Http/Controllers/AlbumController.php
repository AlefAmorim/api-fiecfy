<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function index() {
        $albuns = [
            ['nome' => "The Ultimate Collection", "artista" => "Michael Jackson"],
            ['nome' => "As Vozes Vol 1", "artista" => "Péricles"],
            ['nome' => "As Vozes Vol 2", "artista" => "Péricles"],
            ['nome' => "Swag", "artista" => "Justin Bieber"],
        ];

        return response()->json($albuns);
    }

    public function show($id) {
        return response()->json([
            "status" => 200,
            "mensagem" => "Iniciando busca pelo album $id"
        ]);
    }
}
