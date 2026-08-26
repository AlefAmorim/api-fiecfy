<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = ['name', 'genre', 'profile_pic_url']; //Colunas que podem ser preenchidas no HTML
}
