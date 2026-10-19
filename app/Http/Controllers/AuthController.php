<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        // try {

            $validated = $request->validate([
                "name" => "required|string",
                "email" => "required|string|email",
                "password" => "required|string|min:8|regex:/([A-Z]){1,}([a-z]){1,}([0-9]){1,}([\^\w\_\,]){1,}/"
            ],[
                "name" => "O nome é obrigatório!",
                "email" => "O nome é obrigatório!",
                "password" => "A senha deve conter no mínimo 8 caracteres, 1 letra maiuscula e minuscula, e um caractere especial(@_!)"
            ]);

            // if($validated["errors"]){
            //     return response()->json([
            //         "erro" => "Erro"
            //     ], 400);
            // }
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

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if(!Auth::attempt($credentials)){
            return response()->json(['erro' => "Email ou senha incorretos!"], 400);
        }

        $user =  User::whereEmail($request['email'])->firstOrFail();

        if(!$user) {
            return response()->json([
                "erro" => "Falha ao realizar login!"
            ], 500);
        }

        $token = $user->createToken('auth-token', [])->plainTextToken;


        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }
}
