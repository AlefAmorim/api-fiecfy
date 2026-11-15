<?php

namespace App\Http\Controllers;

use App\Http\Requests\SongsRequest;
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
        $page = $request->query("page");
        $term = $request->query("term");

        $songs = Song::with("album");

        if ($term) $songs = $songs->where("name", "like", "%" . $term . "%");

        return response()->json([
            $limit ? $songs->paginate(perPage: $limit, page: $page) : $songs->get()
        ], 200);
    }

    public function store(SongsRequest $request): JsonResponse
    {
        try {

            $validated = $request->validated();
            $song_album = Album::with("songs")->where("track_number", "=", $validated["track_number"]);
            if ($song_album) {
                return response()->json([
                    "error" => "Songs already in this track."
                ], 400);
            }

            $song = Song::create($validated);
            return response()->json(["message" => "Song created successfully!"], 201);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(["error" => "Album not found!"], 404);
            }
            return response()->json(["error" => "Error creating song!"], 404);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $song = Song::findOrFail($id);

        $song->forceDelete();

        return response()->json([
            "message" => "Song removed successfully!"
        ], 200);
    }

    public function inactivate(int $id): JsonResponse
    {
        $song = Song::findOrFail($id);

        $song->delete();

        return response()->json([
            "message" => "Song inactivated successfully!"
        ], 200);
    }

    public function activate(int $id): JsonResponse
    {
        $song = Song::withTrashed()->where("id", "=", $id);

        $song->restore();

        return response()->json([
            "message" => "Song activated successfully!"
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
                "error" => "Fill the field to update."
            ], 400);
        }

        $song = Song::findOrFail($id);

        $song->update($validated);

        return response()->json([
            "message" => "Song updated successfully!"
        ], 200);
    }
}
