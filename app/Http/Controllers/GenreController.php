<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class GenreController extends Controller
{
    //
    public function index(Request $request): JsonResponse
    {
        $limit = $request->query("limit");
        $genres = $limit ? Genre::paginate($limit) : Genre::all();
        return response()->json([$genres], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            "name" => "required|unique:genres",
            "description" => "required"
        ]);

        Genre::create($validated);

        return response()->json(["message" => "Gênero criado com sucesso!"], 201);
    }

    public function destroy(int $id): JsonResponse
    {
        $genre = Genre::findOrFail($id);
        $genre->forceDelete();

        return response()->json([
            "message" => "Gênero removido com sucesso!"
        ], 200);
    }

    public function incativate(int $id): JsonResponse
    {
        $genre = Genre::findOrFail($id);
        $genre->delete();

        return response()->json([
            "message" => "Gênero desativado com sucesso!"
        ], 200);
    }

    public function activate(int $id): JsonResponse
    {
        $genre = Genre::withTrashed()->where("id", "=", $id);
        $genre->restore();

        return response()->json([
            "message" => "Gênero reativado com sucesso!"
        ], 200);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            "name" => "sometimes|unique:genres"
        ]);

        $genre = Genre::findOrFail($id);
        $genre->update($validated);

        return response()->json(["message" => "Gênero atualizaado com sucesso!"], 201);
    }
}
