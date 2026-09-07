<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = ['artist_id', 'title', 'release_year', 'cover_image'];

    public function artist(){
        // belongsTo diz que esse Model/Entidade pertênce a uma determinada entidade(Relacionamento)
        return $this->belongsTo(Artist::class);
    }
    public function songs(){
        return $this->hasMany(Song::class);
    }
    public function show(int $id) {
        try{
            $album = Album::with('songs')->findOrFail($id);
            
            return response()->json($album,200);
        }catch(Exception $e) {
            return response()->json(["erro"=>"Falha ao buscar album"], 500);
        }
    }
}
