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
        // try {

            $validated = $request->validate([
                "name" => "required|string",
                "email" => "required|string|email",
                "password" => "required|string|min:8"
            ],[
                "name" => "O nome é obrigatório!",
                "email" => "O nome é obrigatório!",
                "password" => "A senha deve conter no mínimo 8 caracteres, 1 letra maiuscula e minuscula, e um caractere especial(@_!)"
            ]);

            if($validated->errors){
                return response()->json([
                    "erro" => "Erro"
                ], 400);
            }
            $validated["password"] = Hash::make($validated["password"]);
            $user = User::create($validated);
    
            return response()->json([
                "mensagem" => "Cadastro efetuado com sucesso!",
                "dados" => $user
            ], 200);
        /*}catch(Exception $ex) {
            if($ex instanceof ValidationException) {
                return response()->json([
                    "erro" => $ex->getMessage()
                ], 400);
            }
            return response()->json([
                "mensagem" => "Erro interno no servidor!"
                ], 500);
        }*/
    } 
}
