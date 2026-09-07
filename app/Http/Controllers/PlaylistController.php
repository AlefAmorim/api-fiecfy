<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Exception;
use FaixaMusical;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
    public function addSong(Request $request, int $id){
        try {
            echo $request->song_id;
            $playlist = Playlist::findOrFail($id);
            $playlist->songs()->attach($request->song_id);

            return response()->json(["mensagem" => "Musica adicionada com sucesso!"], 201);
        }catch(Exception $e){
            echo $e;
            if($e instanceof ModelNotFoundException) {
                return response()->json(["erro" => "Playlist não encontrada!"], 404);
            }
            return response()->json(["erro" => "Falha ao adicionar musica na playlist!"], 500);
        }
    }
    public function index() {
        return response()->json(self::$playlistMock);
    }

    public function store(Request $request){
        try {
            $playlist = Playlist::create($request->all());
            $nome = $playlist->name;

            return response()->json([
            'mensagem' => "Playlist '$nome' criada com sucesso!",
            "dados" => $playlist
        ], 201);
        }catch(Exception $e) {
            return response()->json(['mensagem' => "Erro ao criar playlist!"], 500);
        }
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
