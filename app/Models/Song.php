<?php

namespace App\Models;

use Attribute;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute as CastsAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

use function Illuminate\Support\seconds;

class Song extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ["album_id", "genre_id", "title", "duration_seconds", "is_explicit", "track_number", "audio_path"];
    protected $appends = [
        "duration_formatted"
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, "playlist_song");
    }

    protected function durationFormatted(): CastsAttribute
    {
        return CastsAttribute::make(
            get: fn() => sprintf("%02d:%02d", intdiv($this->duration_seconds, 60), (int) $this->duration_seconds % 60)
        );
    }
    protected function title(): CastsAttribute
    {
        return CastsAttribute::make(
            set: fn(string $value ) => Str::title($value),
        );
    }
}
