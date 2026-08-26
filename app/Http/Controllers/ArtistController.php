<?php


namespace App\Http\Controllers;

use App\Models\Artist;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PDOException;

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
        try{
            $novoArtista = Artist::create($request->all());
            return response()->json(['message'=>'Artista criado com sucesso!',$novoArtista], 201);
        }catch(Exception $ex) {
            if($ex->getCode() == "HY000"){ // Código retornado pelo PDO ao tentar inserir um registro com um campo obrigatório faltando
                return response()->json(['erro'=> "Preencha os dados obrigatórios!"], 400);
            }
            return response()->json(['erro'=>$this->messageInternalServerError], 500);
        }
    }

    public function update(Request $request,int $id){
        try{
            $artista = Artist::findOrFail($id);
    
            $artista->update($request->all());
    
            return response()->json(['message'=> "Artista atualizado com sucesso!", $artista], 200);
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
    
            return response()->json(['message'=>'Artista deletado com sucesso!'], 200);
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
