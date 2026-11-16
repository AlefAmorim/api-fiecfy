<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaylistRequest;
use App\Models\Playlist;
use Exception;
use FaixaMusical;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PlaylistController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $limit = $request->query("limit");
        $page = $request->query("page");
        $playlists = $limit ? Playlist::with("songs")->paginate(perPage: $limit, page: $page) : Playlist::with("songs")->get();
        return response()->json([
            $playlists->toResourceCollection()
        ], 200);
    }

    public function listPlaylistsUser(Request $request): JsonResponse
    {
        $user = $request->user();
        $playlists = $user->playlist()->get();

        return response()->json([
            $playlists->toResourceCollection()
        ], 200);
    }

    public function listPublicPlaylists(Request $request): JsonResponse
    {
        $limit = $request->query("limit");
        $page = $request->query("page");
        $playlists = Playlist::with("songs")->where("public", "=", true);
        $playlists = $limit ? $playlists->paginate(perPage: $limit, page: $page) : $playlists->get();
        

        return response()->json([
            $playlists->toResourceCollection()
        ], 200);
    }

    public function store(PlaylistRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $playlist = Playlist::create($validated);
            $nome = $playlist->name;

            return response()->json([
                'message' => "Playlist created successfully!"
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => "Error creating playlist! Try again later."], 500);
        }
    }

    public function addSong(Request $request, int $id): JsonResponse
    {
        try {
            $playlist = Playlist::findOrFail($id);
            $playlist->songs()->attach($request->song_id);

            return response()->json(["message" => "Songs added successfully!"], 201);
        } catch (Exception $e) {
            echo $e;
            if ($e instanceof ModelNotFoundException) {
                return response()->json(["error" => "Playlist not found!"], 404);
            }
            return response()->json(["error" => "Fail adding music!Try again later."], 500);
        }
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
                "message" => "Fill the field to update."
            ], 400);
        }

        $playlist = Playlist::findOrFail($id);

        $playlist->update($validated);

        return response()->json([
            "message" => "Playlist updated successfully!"
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $playlist = Playlist::findOrFail($id);
        $playlist->forceDelete();

        return response()->json([
            'message' => "Playlist removed successfully!"
        ], 200);
    }

    public function inactivate(int $id): JsonResponse
    {
        $playlist = Playlist::findOrFail($id);
        $playlist->delete();

        return response()->json([
            'message' => "Playlist inactivated successfully!"
        ], 200);
    }

    public function activate(int $id): JsonResponse
    {
        $playlist = Playlist::withTrashed()->where("id", "=", $id);

        $playlist->restore();

        return response()->json([
            "message" => "Playlist activated successfully!"
        ], 200);
    }
}
