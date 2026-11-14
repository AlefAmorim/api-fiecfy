<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Song;
use Exception;
use FaixaMusical;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SongsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $limit = $request->query("limit");
        $songs = $limit ? Song::paginate($limit) : Song::all();

        return response()->json([
            $songs
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        try {

            $validated = $request->validate([
                "album_id" => "required|int",
                "title" => "required",
                "is_explicit" => "required|bool",
                "track_number" => "required",
            ]);
            $song_album = Album::with("songs")->where("track_number", "=", $validated["track_number"]);
            if ($song_album) {
                return response()->json([
                    "error" => "Musica já cadastrada na posição especificada."
                ], 400);
            }

            $song = Song::create($validated);
            return response()->json(["message" => "Música criada com sucesso!"], 201);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(["error" => "Album não encontrado!"], 404);
            }
            return response()->json(["error" => "Falha ao criar musica!"], 404);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $song = Song::findOrFail($id);

        $song->forceDelete();

        return response()->json([
            "message" => "Música deletada com sucesso"
        ], 200);
    }

    public function inactivate(int $id): JsonResponse
    {
        $song = Song::findOrFail($id);

        $song->delete();

        return response()->json([
            "message" => "Música desativada com sucesso"
        ], 200);
    }

    public function activate(int $id): JsonResponse
    {
        $song = Song::withTrashed()->where("id", "=", $id);

        $song->restore();

        return response()->json([
            "message" => "Musica reativada com sucesso!"
        ], 200);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            "album_id" => "sometimes|int",
            "title" => "sometimes",
            "is_explicit" => "sometimes|bool",
            "track_number" => "sometimes",
            "audio_path" => "sometimes",
        ]);

        if (!sizeof($validated)) {
            return response()->json([
                "error" => "Preencha os campos para atualização."
            ], 400);
        }

        $song = Song::findOrFail($id);

        $song->update($validated);

        return response()->json([
            "message" => "Música alterada com sucesso!"
        ], 200);
    }
}
