<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    //
    public function store(Request $request){
        
    } 
    public function upload(Request $request) {
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
