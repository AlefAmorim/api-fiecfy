<?php

namespace App\Http\Controllers;

use FaixaMusical;
use Illuminate\Http\Request;

class MusicaController extends Controller
{
    public function index() {
        $musicas = [
            new FaixaMusical("Billie Jean", "Michael Jackson"),
            new FaixaMusical("Circles", "Post Malone"),
            new FaixaMusical("In the end", "Link Park"),
            new FaixaMusical("Me Dê Motivo", "Tim Maia"),
            new FaixaMusical("Distrimia", "Casuarina"),
            new FaixaMusical("Chove Chuva", "Jorge Ben Jor") ,
        ];

        return response()->json($musicas);
    }
}
