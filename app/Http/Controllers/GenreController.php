<?php

namespace App\Http\Controllers;

use App\Actions\FileStorage;
use App\Models\Genre;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class GenreController extends Controller
{
    private FileStorage $fileHandler;

    public function __construct()
    {
        $this->fileHandler = new FileStorage("public", "genres");
    }
    public function index(Request $request): JsonResponse
    {
        $limit = $request->query("limit");
        $page = $request->query("page");
        $genres = $limit ? Genre::paginate(perPage: $limit, page: $page) : Genre::all();
        return response()->json([$genres->toResourceCollection()], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            "name" => "required|unique:genres",
            "description" => "required"
        ]);

        $genre = Genre::create($validated);

        if($request->hasFile("cover_image")) {
            $file = $request->file("cover_image");

            $name = "genre_cover_" . $genre["id"] . "." . $file->extension();
            $path = $this->fileHandler($file, $name);

            $genre->update([
                "cover_image" => $path
            ]);
        }


        return response()->json(["message" => "Genre created successfully!"], 201);
    }

    public function destroy(int $id): JsonResponse
    {
        $genre = Genre::findOrFail($id);
        $genre->forceDelete();

        return response()->json([
            "message" => "Genre removed successfully!"
        ], 200);
    }

    public function incativate(int $id): JsonResponse
    {
        $genre = Genre::findOrFail($id);
        $genre->delete();

        return response()->json([
            "message" => "Genre inactivated successfully!"
        ], 200);
    }

    public function activate(int $id): JsonResponse
    {
        $genre = Genre::withTrashed()->where("id", "=", $id);
        $genre->restore();

        return response()->json([
            "message" => "Genre activated successfully!"
        ], 200);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            "name" => "sometimes|unique:genres"
        ]);

        $genre = Genre::findOrFail($id);
        $genre->update($validated);

        return response()->json(["message" => "Genre updated successfully!"], 200);
    }
}
