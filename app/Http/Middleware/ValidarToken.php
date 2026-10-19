<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\SanctumServiceProvider;
use Symfony\Component\HttpFoundation\Response;

use function PHPSTORM_META\type;

class ValidarToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        if(!$request->bearerToken()) {
            return response()->json([
                "mensagem" => "Autenticação necessária!"
            ], 403);
        }

        /*
        *
        * $request->bearerToken() - É utilizado para buscar o token passado no header 
        * Authorization.
        * findToken é o método da classe PersonalAccessToken do sanctum, utilizado para para 
        * buscar o token no banco(Retorna um objeto PersonalAccessToken).
        * tokenable é uma propriedade do objeto PersonalAccessTOKEN que contém os dados do usuário 
        * que está ligado ao token retornado.
        *
        */
        $tokenable =  PersonalAccessToken::findToken($request->bearerToken()); 
        // var_dump($tokenable);
        $campos = $request->input("dados");
        foreach($campos as $key => $_valor){
            echo $key . "\n";
        }

        $request["user"] = $tokenable->tokenable;        

        return $next($request);
    }
}
