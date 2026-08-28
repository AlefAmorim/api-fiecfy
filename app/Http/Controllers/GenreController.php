<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    //
    public function store(Request $request) : JsonResponse {
        try {
            $validated = $request->validate([
                "name"=>['required', 'string', 'unique:genres', 'max:255'],
                "description"=>['string']
            ]);
            var_dump($validated);
            $genero = [];
            return response()->json(["mensagem" => "Genêro criado com sucesso!", $genero], 201);
        }   catch(Exception $e) {
            echo $e->getMessage();
            return response()->json(["erro" => "Erro interno no servidor."], 500);
        }
    }
}
