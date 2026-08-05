<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;


class ArtistaController extends Controller
{
    // Método show: Retorna todos os items
    public function index() {
        $colecaoArtistas = [
            ['nome' => 'the weeknd', 'estilo' => 'R&G / Pop'],
            ['nome' => 'Daft punk', 'estilo' => 'Eletrônica Classica']
        ];


        return response()->json([
            'sucesso' => true,
            'dados' => $colecaoArtistas
        ]);
    }


    // Método show: Retorna o item buscado por id
    public function show($id) {
        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Log de busca ativado para o ID de Atista $id'
        ]);
    }
}
