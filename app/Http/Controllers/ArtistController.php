<?php


namespace App\Http\Controllers;

use App\Models\Artist;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    private string $messageInternalServerError = "Falha interna no servidor! Tente novamente mais tarde." ;
    // Método show: Retorna todos os items
    public function index() : JsonResponse {
        try {
            $artistas = Artist::all(); // Equivalente ao "SELECT * FROM tabela"

            return response()->json($artistas, 200);
        }catch(Exception $err) {
            return response()->json(['erro'=> $this->messageInternalServerError], 500);
        }
    }

    // Método show: Retorna o item buscado por id
    public function show(int $id) : JsonResponse {
        try {
            $artista = Artist::findOrFail($id);
            return response()->json($artista, 200);
        }catch(Exception $err) {
            return response()->json(['erro' => $this->messageInternalServerError], 500);
        }
    }

    public function store(Request $request) : JsonResponse{
        $novoArtista = Artist::create($request->all());
        return response()->json($novoArtista, 201);
    }

    public function update(Request $request,int $id){
        try{
            $artista = Artist::findOrFail($id);
    
            $artista->update($request->all());
    
            return response()->json($artista, 200);
        }catch(Exception $err) {
            if($err instanceof ModelNotFoundException) {
                return response()->json(['erro' => "Erro ao atualizar artista com o id $id! Artista não encontrado"], 404);
            }
            return response()->json(['erro'=> $this->messageInternalServerError], 500);
        }
    }

    public function destroy(int $id) {
        try {
            $artista = Artist::findOrFail($id);
    
            $artista->delete();
    
            return response()->json(['mensagem'=>'Artista apagado com sucesso'], 200);

        }catch(Exception $e) {
            // verifica se o tipo da exceção é ModelNotFoundException
            // ModelNotFoundException é a exceção lançada pelo método findOrFail caso não encontre o registro
            if($e instanceof ModelNotFoundException){
                return response()->json(['erro' => "Erro ao deletar artista com id $id!Atista não encontrado."], 404);
            }

            return response()->json(['erro'=> $this->messageInternalServerError], 500);
        }
    }
}
