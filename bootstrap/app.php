<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->dontReportDuplicates(); // Faz com que a mesma instância de uma Exceção não seja lançada(throw) duas vezes
    })
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([ //Para registrar middlewares adicionais
            'abilities' => CheckAbilities::class,//Middleware do sanctum para verificação das habilidades dos tokens
            'ability' => CheckForAnyAbility::class // O mesmo que o de cima 
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
