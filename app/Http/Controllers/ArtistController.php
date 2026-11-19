<?php


namespace App\Http\Controllers;

use App\Actions\FileStorage;
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
    private FileStorage $fileHandler;

    public function __construct()
    {
        $this->fileHandler = new FileStorage("public", "artists");
    }

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

            if ($request->hasFile("profile_pic_url")) {
                $file = $request->file("profile_pic_url");
                $name = "artist_pic_" . $newArtist["id"] . '.' . $file->extension();
                $path = $this->fileHandler->store($file, $name);

                $newArtist->update([
                    "profile_pic_url" => $path
                ]);
            }
            return response()->json(['message' => 'Artist created successfully!'], 201);
        } catch (Exception $ex) {
            if ($ex->getCode() == "HY000") { // Código retornado pelo PDO ao tentar inserir um registro com um campo obrigatório faltando
                return response()->json(['error' => "Fill the required fields."], 400);
            }
            // return response()->json(['error' => $this->messageInternalServerError], 500);
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                "name" => "sometimes|string",
                "monthly_listeners" => "sometimes|int",
                "genre_id" => "sometimes|int",
            ]);
            $artist = Artist::findOrFail($id);
            if ($request->hasFile("profile_pic_url")) {
                $file = $request->file("profile_pic_url");
                $fileName = 'artist_pic_' . $artist['id'] . '.' . $file->extension();
                $path = $this->fileHandler->update($file, $fileName);
                if ($path !== $artist->profile_pic_url) {
                    $artist->update([
                        "profile_pic_url" => $path
                    ]);
                }
            }
            $artist->update($validated);

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
