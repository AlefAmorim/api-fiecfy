<?php

namespace App\Http\Controllers;

use App\Actions\FileStorage;
use App\Http\Requests\AlbumRequest;
use App\Models\Album;
use App\Models\Artist;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    private string $messageInternalServerError = "Internal server error!Try again later.";
    private FileStorage $fileHandler;

    public function __construct()
    {
        $this->fileHandler = new FileStorage("public", "albums");
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $limit = $request->query("limit");
            $page = $request->query("page");
            $term = $request->query("term");

            $albums = Album::with("artist");
            if ($term) $albums->where("name", "like", "%" . $term . "%");
            $albums = $limit ? $albums->paginate(perPage: $limit, page: $page) : $albums->get();

            return response()->json($albums->toResourceCollection(), 200);
        } catch (Exception $ex) {
            return response()->json(['error' => $this->messageInternalServerError], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $album = Album::with('songs')->findOrFail($id);

            return response()->json($album->toResource(), 200);
        } catch (Exception $ex) {
            if ($ex instanceof ModelNotFoundException) {
                return response()->json(['error' => 'Album not  found.'], 404);
            }
            return response()->json(['error' => $this->messageInternalServerError], 500);
        }
    }

    public function store(AlbumRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            Artist::findOrFail($validated["artist_id"]); // Verifica se o artista existe
            $album = Album::create($validated);
            if ($request->hasfile('cover_image')) {
                $file = $request->file("cover_image");
                $name = 'cover_album_' . $album['id'] . '.' . $file->extension();
                // $path = Storage::disk("public")->putFileAs('albums', $request['cover_image'], $name);
                $path = $this->fileHandler->store($file, $name);

                $album->update(['cover_image' => $path]);
            }

            return response()->json(['message' => 'Album added successfully!'], 201);
        } catch (Exception $ex) {
            // Tratamento caso haja algum campo obrigatório faltando
            if ($ex->getCode() == "HY000") {
                return response()->json(['error' => "Fill the required fields."], 400);
            }

            //Tratamento caso o artista referenciado não exista
            if ($ex instanceof ModelNotFoundException) {
                return response()->json(['error' => "Albums artist not found."], 404);
            }
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                "artist_id" => "sometimes|int",
                "release_year" => "sometimes|int",
                "title" => "sometimes|string"
            ]);
            $album = Album::findOrFail($id);
            if ($request->hasFile("cover_image")) {
                $file = $request->file("cover_image");
                $fileName = 'cover_album_' . $album['id'] . '.' . $file->extension();
                $path = $this->fileHandler->update($file, $fileName);
                if ($path !== $album->cover_image) {
                    $album->update([
                        "cover_image" => $path
                    ]);
                }
            }
            $album->update($validated);

            return response()->json(['message' => 'Album updated successfully!'], 200);
        } catch (Exception $ex) {
            if ($ex->getCode() == "HY000") {
                return response()->json(['error' => "Fill the required fields."], 400);
            }

            if ($ex instanceof ModelNotFoundException) {
                return response()->json(['error' => "Album not found."], 404);
            }
            return response()->json(['error' => $this->messageInternalServerError], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $album = Album::findOrFail($id);

            $album->forceDelete();

            return response()->json(['message' => "Album removed successfully!"], 200);
        } catch (Exception $ex) {
            if ($ex instanceof ModelNotFoundException) {
                return response()->json(['error' => "Album not found."], 404);
            }
            return response()->json(['error' => $this->messageInternalServerError], 500);
        }
    }

    public function inactivate(int $id): JsonResponse
    {
        $album = Album::findOrFail($id);

        $album->delete();

        return response()->json([
            "message" => "Album inactivaded successfully!"
        ], 200);
    }

    public function activate(int $id): JsonResponse
    {
        $album = Album::withTrashed()->where("id", "=", $id);

        $album->restore();

        return response()->json([
            "message" => "Album activated successfully!"
        ], 200);
    }
}
