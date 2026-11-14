<?php

namespace App\Http\Controllers;

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
    public function store(Request $request) : JsonResponse {
        $validated = $request->validate([
            "name" => "required",
            "email" => "required|email",
            "password" => "required|min:8",
        ]);

        User::create($validated);

        return response()->json([
            "mensagem" => "Usuário criado com sucesso!"
        ], 201);
    } 

    public function upload(Request $request): JsonResponse  {
        $user = $request->user();

        $validated = $request->validate([
            "pic_url" => "required|mimes:jpeg,png,gif,pdf,doc,docx|max:5000"
        ]);

        if($request->hasFile("pic_url")){
            $file = $request->file("pic_url");
            $nomeImg = time() . "-" . $file->getClientOriginalName();

            $file->move(public_path('img/perfil'), $nomeImg);
    
            $validated['pic_url'] = 'img/perfil/' . $nomeImg;
    
            $user->update($validated);
    
            return response()->json([
                "mensagem" => "Imagem alterada com sucesso!",
                "dados" => $user
            ], 200);
        }

        return response()->json([
                "mensagem" => "Envie a imagem para upload!"
            ], 400);

    }
}
