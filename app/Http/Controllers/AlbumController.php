<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AlbumController extends Controller
{
    private string $messageInternalServerError = "Falha no servidor!Tente novamente mais tarde.";

    public function index(Request $request): JsonResponse
    {
        try {
            $limit = $request->query("limit");
            $albums = $limit ? Album::with("artists")->paginate($limit) : Album::with("artists")->get(); // Retorna  todos os registros da tabela

            return response()->json($albums, 200);
        } catch (Exception $ex) {
            return response()->json(['error' => $this->messageInternalServerError], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $album = Album::with('songs')->findOrFail($id);

            return response()->json($album, 200);
        } catch (Exception $ex) {
            if ($ex instanceof ModelNotFoundException) {
                return response()->json(['error' => 'Album não encontrado!'], 404);
            }
            return response()->json(['error' => $this->messageInternalServerError], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                "artist_id" => "required",
                "title" => "required",
                "release_year" => "required"
            ]);

            Artist::findOrFail($validated["artist_id"]); // Verifica se o artista existe
            $album = Album::create($validated);

            return response()->json(['message' => 'Album adicionado com sucesso!'], 201);
        } catch (Exception $ex) {
            // Tratamento caso haja algum campo obrigatório faltando
            if ($ex->getCode() == "HY000") {
                return response()->json(['error' => "Preencha os dados obrigatórios!"], 400);
            }

            //Tratamento caso o artista referenciado não exista
            if ($ex instanceof ModelNotFoundException) {
                return response()->json(['error' => "Artista referenciado não encontrado!"], 404);
            }
            return response()->json(['error' => $this->messageInternalServerError], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $album = Album::findOrFail($id);
            $album->update($request->all());

            return response()->json(['message' => 'Album atualizado com sucesso!'], 200);
        } catch (Exception $ex) {
            if ($ex->getCode() == "HY000") {
                return response()->json(['error' => "Preencha os dados obrigatórios!"], 400);
            }

            if ($ex instanceof ModelNotFoundException) {
                return response()->json(['error' => "Album não encontrado!"], 404);
            }
            return response()->json(['error' => $this->messageInternalServerError], 500);
        }
    }
    public function destroy(int $id): JsonResponse
    {
        try {
            $album = Album::findOrFail($id);

            $album->forceDelete();

            return response()->json(['message' => "Album deletado com sucesso!"], 200);
        } catch (Exception $ex) {
            if ($ex instanceof ModelNotFoundException) {
                return response()->json(['error' => "Album não encontrado!"], 404);
            }
            return response()->json(['error' => $this->messageInternalServerError], 500);
        }
    }

    public function inactivate(int $id): JsonResponse
    {
        $album = Album::findOrFail($id);

        $album->delete();

        return response()->json([
            "message" => "Album desativado com sucesso!"
        ], 200);
    }

    public function activate(int $id): JsonResponse
    {
        $album = Album::withTrashed()->where("id", "=", $id);

        $album->restore();

        return response()->json([
            "message" => "Album reativado com sucesso!"
        ], 200);
    }
}
