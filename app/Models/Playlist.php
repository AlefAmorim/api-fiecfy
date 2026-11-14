<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Playlist extends Model
{
    use SoftDeletes;
    //
    protected $fillable = ["user_id", "name", "public", "picture_url"];

    public function users() {
        return $this->belongsTo(User::class);
    }

    public function songs() {
        return $this->hasMany(Song::class);
    }
}
