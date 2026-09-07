<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    private string $messageInternalServerError = "Falha no servidor!Tente novamente mais tarde.";

    public function index() : JsonResponse {
        try {
            $albums = Album::all(); // Retorna  todos os registros da tabela

            return response()->json( $albums,200);
        } catch(Exception $ex){
            return response()->json(['erro'=>$this->messageInternalServerError],500);
        }
    }

    public function show(int $id) : JsonResponse {
        try {
            $album = Album::with('songs')->findOrFail($id);

            return response()->json($album, 200);
        }catch(Exception $ex) {
            if($ex instanceof ModelNotFoundException) {
                return response()->json(['erro' => 'Album não encontrado!'], 404);
            }
            return response()->json(['erro' => $this->messageInternalServerError], 500);
        } 
    }

    public function store(Request $request) : JsonResponse {
        try {
            Artist::findOrFail($request->input("artist_id")); // Verifica se o artista existe
            $album = Album::create($request->all());

            return response()->json(['message' => 'Album adicionado com sucesso!', $album], 201);
        }catch(Exception $ex) {
            // Tratamento caso haja algum campo obrigatório faltando
            if($ex->getCode() == "HY000"){ 
                return response()->json(['erro'=> "Preencha os dados obrigatórios!"], 400);
            }

            //Tratamento caso o artista referenciado não exista
            if($ex instanceof ModelNotFoundException){
                return response()->json(['erro'=> "Artista referenciado não encontrado!"], 404);
            }
            return response()->json(['erro' => $this->messageInternalServerError], 500);
        }
    }

    public function update(Request $request, int $id) : JsonResponse {
        try {
            $album = Album::findOrFail($id);
            $album->update($request->all());

            return response()->json(['message'=>'Album atualizado com sucesso!', $album], 200);
        }catch(Exception $ex) {
            if($ex->getCode() == "HY000"){ 
                return response()->json(['erro'=> "Preencha os dados obrigatórios!"], 400);
            }

            if($ex instanceof ModelNotFoundException){
                return response()->json(['erro'=> "Album não encontrado!"], 404);
            }
            return response()->json(['erro'=> $this->messageInternalServerError], 500);
        }
    }
    public function destroy(int $id) {
        try {
            $album = Album::findOrFail($id);

            $album->delete();

            return response()->json(['message' => "Album deletado com sucesso!"], 200);
        }catch(Exception $ex) {
            if($ex instanceof ModelNotFoundException){
                return response()->json(['erro'=> "Album não encontrado!"], 404);
            }
            return response()->json(['erro'=> $this->messageInternalServerError], 500);
        }
    }
}
