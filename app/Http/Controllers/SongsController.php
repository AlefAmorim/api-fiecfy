<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Exception;
use FaixaMusical;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SongsController extends Controller
{
    public function index() {
        $musicas = [
            new FaixaMusical("Billie Jean", "Michael Jackson"),
            new FaixaMusical("Circles", "Post Malone"),
            new FaixaMusical("In the end", "Link Park"),
            new FaixaMusical("Me Dê Motivo", "Tim Maia"),
            new FaixaMusical("Distrimia", "Casuarina"),
            new FaixaMusical("Chove Chuva", "Jorge Ben Jor") ,
        ];

        return response()->json($musicas);
    }

    public function store(Request $request) : JsonResponse {
        try {
            $song = Song::create($request->all());
            return response()->json(["mensagem" => "Musica criada com sucesso!"]);
        }catch(Exception $e){
            if($e instanceof ModelNotFoundException){
                return response()->json(["erro"=>"Album não encontrado!"], 404);
                }
                return response()->json(["erro"=>"Falha ao criar musica!"], 404);
        }
    }
}
