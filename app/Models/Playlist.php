<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Playlist extends Model
{
    use SoftDeletes;
    //
    protected $fillable = ["name", "picture_url"];


    public function songs() {
        return $this->belongsToMany(Song::class);
    }
}
