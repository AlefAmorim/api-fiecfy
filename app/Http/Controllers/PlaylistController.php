<?php

namespace App\Http\Controllers;

use FaixaMusical;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    private static $playlistMock = [];

    public function __construct()
    {
        self::$playlistMock = [
            ['nome' => 'Verão', 'criada_em' => '20/10/2019', 'musicas' => [
                    new FaixaMusical("Stolen Dance", "Milky Chance"),
                    new FaixaMusical("Smells Like a Teen Spirit", "Nirvana"),
                    new FaixaMusical("Emptiness Machine", "Link Park")
                ]
            ],
            ['nome' => 'Só hit', 'criada_em' => '01/01/2021', 'musicas' => [
                    new FaixaMusical("Radiactive", "Imagine Dragons"),
                    new FaixaMusical("Get Lucky", "Draft Punk"),
                    new FaixaMusical("Feel Good", "Gorilaz")
                ]
            ],
            ['nome' => 'Praia', 'criada_em' => '01/08/2026', 'musicas' => [
                    new FaixaMusical("Borderline", "Tame Impala"),
                    new FaixaMusical("Loser", "Tame Impala"),
                    new FaixaMusical("Azul da Cor do Mar", "Tim Maia")
                ]
            ]
        ]; 
    }

    public function index() {
        return response()->json(self::$playlistMock);
    }

    public function store(Request $request){
        $nome = $request->input('nome_playlist');

        return response()->json([
            'status' => 'sucesso',
            'mensagem' => "Playlist '$nome' criada com sucesso!",
            "dados" => $request->all()
        ], 201);
    }

    public function destroy($id) {
        // Segundo a documentação do MDN, o status code poderia ser 201 ou 204(No Content)
        // Coloquei 201 porque o 204 não retorna o conteúdo json, como dito no próprio nome
        return response()->json([
            'status' => 'sucesso',
            'mensagem' => "Playlist com o id $id excluida com sucesso!"
        ], 201);
    }
}
