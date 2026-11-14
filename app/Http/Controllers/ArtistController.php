<?php


namespace App\Http\Controllers;

use App\Models\Artist;
use Error;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PDOException;

class ArtistController extends Controller
{
    private string $messageInternalServerError = "Falha interna no servidor! Tente novamente mais tarde.";
    // Método show: Retorna todos os items
    public function index(Request $request): JsonResponse
    {
        try {
            $limit = $request->query("limit");
            $artistas = $limit ? Artist::with("genres")->paginate($limit) : Artist::with("genres")->get(); // Equivalente ao "SELECT * FROM tabela"

            return response()->json([$artistas], 200);
        } catch (Exception $err) {
            return response()->json(['error' => $this->messageInternalServerError], 500);
        }
    }

    // Método show: Retorna o item buscado por id
    public function show(int $id): JsonResponse
    {
        try {
            $artista = Artist::with("albums")->findOrFail($id);
            return response()->json([$artista], 200);
        } catch (Exception $err) {
            if ($err instanceof ModelNotFoundException) {
                return response()->json(['erro' => "Artista não encontrado!"], 404);
            }
            return response()->json(['erro' => $this->messageInternalServerError], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                "name" => "required",
                "genre_id" => "required",
            ]);
            $novoArtista = Artist::create($validated);
            return response()->json(['message' => 'Artista criado com sucesso!'], 201);
        } catch (Exception $ex) {
            if ($ex->getCode() == "HY000") { // Código retornado pelo PDO ao tentar inserir um registro com um campo obrigatório faltando
                return response()->json(['erro' => "Preencha os dados obrigatórios!"], 400);
            }
            return response()->json(['erro' => $this->messageInternalServerError], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $artista = Artist::findOrFail($id);

            $artista->update($request->all());

            return response()->json(['message' => "Artista atualizado com sucesso!"], 200);
        } catch (Exception $err) {
            if ($err instanceof ModelNotFoundException) {
                return response()->json(['erro' => "Erro ao atualizar artista!Artista não encontrado."], 404);
            }
            return response()->json(['erro' => $this->messageInternalServerError], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $artista = Artist::findOrFail($id);

            $artista->forceDelete();

            return response()->json(['message' => 'Artista deletado com sucesso!'], 200);
        } catch (Exception $e) {
            // verifica se o tipo da exceção é ModelNotFoundException
            // ModelNotFoundException é a exceção lançada pelo método findOrFail caso não encontre o registro
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['erro' => "Erro ao deletar artista com id $id!Atista não encontrado."], 404);
            }

            return response()->json(['erro' => $this->messageInternalServerError], 500);
        }
    }

    public function inactivate(int $id): JsonResponse
    {
        $artist = Artist::findOrFail($id);

        $artist->delete();

        return response()->json([
            "message" => "Artista desativado com sucesso!"
        ], 200);
    }

    public function activate(int $id): JsonResponse
    {
        $artist = Artist::withTrashed()->where("id", "=", $id);

        $artist->restore();

        return response()->json([
            "message" => "Artista reativado com sucesso!"
        ], 200);
    }
}
