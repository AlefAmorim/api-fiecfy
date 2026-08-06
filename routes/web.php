<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistaController;
use App\Http\Controllers\MusicaController;
use Illuminate\Support\Facades\Route;


class FaixaMusical {
    public string $titulo;
    public string $artista;


    public function __construct(string $titulo, string $artista)
    {
        $this->titulo = $titulo;
        $this->artista = $artista;
    }


    public function getDetalhes() : string{
        return "Título: $this->titulo Artista: $this->artista";
    }


}

class Artista {
    public string $nome;
    public string $estilo;
    public int  $ouvintesMensais;


    // Atribui um valor padrão para o parâmetro $ouvintesMensais
    // assim caso não seja fornecido iniciará com 0
    // e não ocasionará em erro por não fornecimento do parâmetro, como na rota ex3
    public function __construct(string $nome, string $estilo, int $ouvintesMensais = 0)
    {
        $this->nome = $nome;
        $this->estilo = $estilo;
        $this->ouvintesMensais = $ouvintesMensais;
    }


    public function isFamoso() {
        $minimoOuvintes = 1000000;
        return $this->ouvintesMensais > $minimoOuvintes ;
    }
}


Route::get('/', function () {
    return view('welcome');
});


Route::get("/info", function () {
    return [
        'sistema' => 'API Clone Spotify',
        'versao' => '1.0',
        'status' => 'conectado'
    ];
});


Route::get('/minha-musica', function () {
    $faixa = new FaixaMusical("Back In Black", "AC/DC");


    return response()->json($faixa);
});


Route::get('/ex3', function () {
    $artista = new Artista("Péricles", "Pagode");


    return response()->json($artista);
});


Route::get('/ex4', function () {
    $artista = new Artista("Post Malone", "Pop", 1000001);
    $isFamoso =  $artista->isFamoso();


    return response()->json($isFamoso);
});


Route::get('/ex5', function () {
    $faixa1 = new FaixaMusical("Back In Black", "AC/DC");
    $faixa2 = new FaixaMusical("Melhor eu ir", "Péricles");
    $faixa3 = new FaixaMusical("Everybody Loves Somebody", "Dean Martin");


    $playlist = [
        "nome" => "Playlist de verão",
        "criador" => "Usuário 01",
        "musicas" => [
            $faixa1,
            $faixa2,
            $faixa3
        ]
    ];


    return response()->json($playlist);
});


Route::get('/type-error', function (){
    // A instanciação abaixo carregará uma paǵina indicando um TypeError
    // o terceiro parâmetro passado para o construtor espera um valor int e eu estou passando uma string
    $artista = new Artista("Michael Jackson", "Pop", "1Bilhão+");

    return response()->json($artista);
});


// Rotas de Artistas
Route::get("/artistas", [ArtistaController::class, 'index']);
Route::get("/artistas/{id}", [ArtistaController::class, 'show']);

// Rotas de Albums
Route::get('/albums', [AlbumController::class, 'index']);
Route::get('/albums/{id}', [AlbumController::class, 'show']); //Se o id não tiver as chaves, será interpretado como um endpoint

// Rotas de muscas 
Route::get('/musicas', [MusicaController::class, 'index']);