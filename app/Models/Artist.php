<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = ['name', 'genre', 'profile_pic_url']; //Colunas que podem ser preenchidas no HTML
    //Métodos de manipulação da entidade no banco são herdadas da classe Model do Eloquent

    public function albums() {
        
        return $this->hasMany(Album::class);
    }
}
