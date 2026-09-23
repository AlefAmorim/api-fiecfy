<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class Song extends Model
{
    use HasFactory;
    protected $fillable = ["album_id", "title", "duration_seconds", "is_explicit", "track_number", "audio_path"];

    public function album(){
        return $this->belongsTo(Album::class);
    }
    public function playlists(){
        return $this->belongsToMany(Playlist::class);
    }
}
