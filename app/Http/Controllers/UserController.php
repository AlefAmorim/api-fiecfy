<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    //
    public function index(Request $request): JsonResponse
    {
        $limit = $request->query("limit");
        $page = $request->query("page");
        $term = $request->query("term");
        $users = User::where("name", "like", "%".$term."%");

        $users = $limit ? $users->paginate(perPage: $limit, page: $page) : $users->get();

        return response()->json([
            $users->toResourceCollection()
        ], 200);
    }
    public function store(UsersRequest $request): JsonResponse
    {
        $validated = $request->validated();

        User::create($validated);

        return response()->json([
            "mensagem" => "User created successfully!"
        ], 201);
    }

    public function upload(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            "pic_url" => "required|mimes:jpeg,png,gif,pdf,doc,docx|max:5000"
        ]);

        if ($request->hasFile("pic_url")) {
            $file = $request->file("pic_url");
            $nomeImg = time() . "-" . $file->getClientOriginalName();

            $file->move(public_path('img/perfil'), $nomeImg);

            $validated['pic_url'] = 'img/perfil/' . $nomeImg;

            $user->update($validated);

            return response()->json([
                "mensagem" => "Image updated successfully!",
                "dados" => $user
            ], 200);
        }

        return response()->json([
            "mensagem" => "Send an image to upload!"
        ], 400);
    }
}
