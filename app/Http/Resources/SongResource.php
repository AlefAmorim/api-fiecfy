<?php

namespace App\Http\Resources;

use App\Models\Album;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SongResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [            
            "id" => $this->id,
            "duration_seconds" => $this->duration_seconds,
            "title" => $this->title,
            "is_explicit" => $this->is_explicit,
            "track_number" => $this->track_number,
            "audio_path" => $this->audio_path,
            "created_at" => $this->created_at,
            "album" => $this->whenLoaded("album", new AlbumResource(Album::findOrFail($this->album_id))),
            "genre" => $this->whenLoaded("genre", new GenreResource(Genre::findOrFail($this->genre_id)))
        ];
    }
}
