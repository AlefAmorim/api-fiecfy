<?php


namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use Throwable;

class ArtistaController extends Controller
{
    // Método show: Retorna todos os items
    public function index() {
        $artistas = Artist::all(); // Equivalente ao "SELECT * FROM tabela"

        return response()->json($artistas, 200);
    }

    // Método show: Retorna o item buscado por id
    public function show(int $id) {
        $artista = Artist::findOrFail($id);

        return response()->json($artista, 200);
    }

    public function store(Request $request) {
        $novoArtista = Artist::create($request->all());
         return response()->json($novoArtista, 201);
    }

    public function update(Request $request,int $id){
        try{

            $artista = Artist::findOrFail($id);
    
            $artista->update($request->all());
    
            return response()->json($artista, 200);
        }catch(Throwable $err) {
            return response()->json($err, 500);
        }
    }

    public function destroy(int $id) {
        $artista = Artist::findOrFail($id);

        $artista->delete();

        return response()->json(['mensagem'=>'Apagado com sucesso'], 200);
    }
}
