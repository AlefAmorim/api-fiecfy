<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artist extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'genre', 'profile_pic_url']; //Colunas que podem ser preenchidas no HTML
    //Métodos de manipulação da entidade no banco são herdadas da classe Model do Eloquent

    public function albums() {
        return $this->hasMany(Album::class);
    }
}
