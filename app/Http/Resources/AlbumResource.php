<?php

namespace App\Http\Resources;

use App\Models\Artist;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlbumResource extends JsonResource
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
            "title" => $this->title,
            "artist" => $this->whenLoaded('artist', new ArtistResource(Artist::findOrFail($this->artist_id))),
            "songs" => $this->whenLoaded('songs', $this->songs->toResourceCollection()),
            "release_year" => $this->release_year,
            "cover_image" => $this->cover_image,
        ];
    }
}
