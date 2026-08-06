<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function index() {
        $albuns = [
            ['nome' => "The Ultimate Collection", "artista" => "Michael Jackson", "ano_lancamento" => 1980],
            ['nome' => "As Vozes Vol 1", "artista" => "Péricles", "ano_lancamento" => 2012],
            ['nome' => "As Vozes Vol 2", "artista" => "Péricles", "ano_lancamento" => 2012],
            ['nome' => "Swag", "artista" => "Justin Bieber", "ano_lancamento" => 2015],
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
