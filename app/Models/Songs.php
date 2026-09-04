<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Songs extends Model
{
    //
    protected $fillable = ["album_id", "title", "duration_seconds", "is_explicit", "track_number", "audio_path"];
}
