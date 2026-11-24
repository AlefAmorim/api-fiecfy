<?php

namespace App\Http\Controllers;

use App\Actions\FileStorage;
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
    private FileStorage $fileHandler;

    public function __construct()
    {
        $this->fileHandler = new FileStorage("public", "users");
    }
    public function index(Request $request): JsonResponse
    {
        $limit = $request->query("limit");
        $page = $request->query("page");
        $term = $request->query("term");
        $users = User::where("name", "like", "%" . $term . "%");

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
            "message" => "User created successfully!"
        ], 201);
    }

    public function upload(Request $request): JsonResponse
    {
        $user = $request->user();

        // $validated = $request->validate([
        //     "pic_url" => "required|mimes:jpeg,png,gif,webp|max:5000"
        // ]);

        if ($request->hasFile("profile_pic")) {
            $file = $request->file("profile_pic");
            $nomeImg = "profile_pic_" . $user->id . "." . $file->extension();
            $path = $this->fileHandler->store($file, $nomeImg);

            $user->update([
                "pic_url" => $path
            ]);

            return response()->json([
                "message" => "Image updated successfully!",
                "path" => $path
            ], 200);
        }

        return response()->json([
            "message" => "Send an image to upload!"
        ], 400);
    }
}
