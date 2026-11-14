<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Exception;
use FaixaMusical;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PlaylistController extends Controller
{
    public function addSong(Request $request, int $id): JsonResponse
    {
        try {
            echo $request->song_id;
            $playlist = Playlist::findOrFail($id);
            $playlist->songs()->attach($request->song_id);

            return response()->json(["message" => "Musica adicionada com sucesso!"], 201);
        } catch (Exception $e) {
            echo $e;
            if ($e instanceof ModelNotFoundException) {
                return response()->json(["error" => "Playlist não encontrada!"], 404);
            }
            return response()->json(["error" => "Falha ao adicionar musica na playlist!"], 500);
        }
    }

    public function index(Request $request): JsonResponse
    {
        $limit = $request->query("limit");
        $playlists = $limit ? Playlist::with("songs")->paginate($limit) : Playlist::with("songs")->get();
        return response()->json([
            $playlists
        ], 200);
    }

    public function listPlaylistsUser(Request $request): JsonResponse
    {
        $user = $request->user();
        $playlists = $user->playlist()->get();

        return response()->json([
            $playlists
        ], 200);
    }

    public function listPublicPlaylists(Request $request): JsonResponse
    {
        $limit = $request->query("limit");
        $playlists = $limit ? Playlist::with("songs")->paginate($limit)->where("public", "=", true) : Playlist::with("songs")->get()->where("public", "=", true);

        return response()->json([
            $playlists
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $playlist = Playlist::create($request->all());
            $nome = $playlist->name;

            return response()->json([
                'message' => "Playlist '$nome' criada com sucesso!",
                "data" => $playlist
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => "Erro ao criar playlist!"], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $playlist = Playlist::findOrFail($id);
        $playlist->forceDelete();

        return response()->json([
            'message' => "Playlist excluída com sucesso!"
        ], 200);
    }

    public function inactivate(int $id): JsonResponse
    {
        $playlist = Playlist::findOrFail($id);
        $playlist->delete();

        return response()->json([
            'message' => "Playlist desitavada com sucesso!"
        ], 200);
    }

    public function activate(int $id): JsonResponse
    {
        $playlist = Playlist::withTrashed()->where("id", "=", $id);

        $playlist->restore();

        return response()->json([
            "message" => "Playlist reativada com sucesso!"
        ], 200);
    }
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            "name" => "sometimes",
            "public" => "sometimes|bool",
            "picture_url" => "sometimes"
        ]);

        if (!count($validated)) {
            return response()->json([
                "message" => "Informe os campos para alteração."
            ], 400);
        }

        $playlist = Playlist::findOrFail($id);

        $playlist->update($validated);

        return response()->json([
            "message" => "Playlist atualizada com sucesso!"
        ], 200);
    }
}
