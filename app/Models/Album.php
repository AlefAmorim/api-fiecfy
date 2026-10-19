<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory; // Utilizar sempre que tiver um factory
    protected $fillable = ['artist_id', 'title', 'release_year', 'cover_image'];

    public function artist(){
        // belongsTo diz que esse Model/Entidade pertênce a uma determinada entidade(Relacionamento)
        return $this->belongsTo(Artist::class);
    }
    public function songs(){
        return $this->hasMany(Song::class);
    }
}
