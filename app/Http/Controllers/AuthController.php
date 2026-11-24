<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use App\Mail\WelcomeMail;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(AuthRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated["password"] = Hash::make($validated["password"]);
        $user = User::create($validated);
        Mail::to($user, $user->name)->send(new WelcomeMail($user->name));
        return response()->json([
            "menssge" => "Register made successfully!",
            "data" => $user
        ], 200);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            "email" => "required|email",
            "password" => "required|min:8"
        ]); 

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => "Email or password wrong!"], 400);
        }

        $user =  User::whereEmail($request['email'])->firstOrFail();

        if (!$user) {
            return response()->json([
                "error" => "Fail on login! Try again later."
            ], 500);
        }

        $token = $user->createToken('auth-token')->plainTextToken;


        return response()->json([
            'tkn' => $token,
            'data' => $user
        ]);
    }
    public function me(Request $request)
    {
        $user = $request->user()->toResource();
            return response()->json([
                "data" => $user
            ], 200);    
    }
}
