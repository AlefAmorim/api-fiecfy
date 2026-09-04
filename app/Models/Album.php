<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = ['artist_id', 'title', 'release_year', 'cover_image'];

    public function albums(){
        // Diz que o Album têm muitos artistas
        return $this->hasMany(Album::class);
    }
    public function songs(){
        return $this->hasMany(Songs::class);
    }
}
