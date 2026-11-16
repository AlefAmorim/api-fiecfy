<?php


namespace App\Http\Controllers;

use App\Http\Requests\ArtistRequest;
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

    public function index(Request $request): JsonResponse
    {
        try {
            $limit = $request->query("limit");
            $page = $request->query("page");
            $term = $request->query("term");

            $artists = Artist::with("genre");
            if ($term) $artists->where("name", "like", "%" . $term . "%");

            $artists = $limit ? $artists->paginate(perPage: $limit, page: $page) : $artists->get(); // Equivalente ao "SELECT * FROM tabela"

            return response()->json([$artists->toResourceCollection()], 200);
        } catch (Exception $err) {
            return response()->json(['error' => $this->messageInternalServerError], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $artist = Artist::with("albums")->findOrFail($id);
            return response()->json([$artist->toResource()], 200);
        } catch (Exception $err) {
            if ($err instanceof ModelNotFoundException) {
                return response()->json(['error' => "Artist not found!"], 404);
            }
            return response()->json(['error' => $this->messageInternalServerError], 500);
        }
    }

    public function store(ArtistRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $newArtist = Artist::create($validated);
            return response()->json(['message' => 'Artist created successfully!'], 201);
        } catch (Exception $ex) {
            if ($ex->getCode() == "HY000") { // Código retornado pelo PDO ao tentar inserir um registro com um campo obrigatório faltando
                return response()->json(['error' => "Fill the required fields."], 400);
            }
            return response()->json(['error' => $this->messageInternalServerError], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $artist = Artist::findOrFail($id);

            $artist->update($request->all());

            return response()->json(['message' => "Artist updated successfully!"], 200);
        } catch (Exception $err) {
            if ($err instanceof ModelNotFoundException) {
                return response()->json(['error' => "Error updating!Artist not found."], 404);
            }
            return response()->json(['error' => $this->messageInternalServerError], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $artist = Artist::findOrFail($id);

            $artist->forceDelete();

            return response()->json(['message' => 'Artista removed successfully!'], 200);
        } catch (Exception $e) {
            // verifica se o tipo da exceção é ModelNotFoundException
            // ModelNotFoundException é a exceção lançada pelo método findOrFail caso não encontre o registro
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['error' => "Artist not found."], 404);
            }

            return response()->json(['error' => $this->messageInternalServerError], 500);
        }
    }

    public function inactivate(int $id): JsonResponse
    {
        $artist = Artist::findOrFail($id);

        $artist->delete();

        return response()->json([
            "message" => "Artist inactivated successfully!"
        ], 200);
    }

    public function activate(int $id): JsonResponse
    {
        $artist = Artist::withTrashed()->where("id", "=", $id);

        $artist->restore();

        return response()->json([
            "message" => "Artist activated successfully!"
        ], 200);
    }
}
