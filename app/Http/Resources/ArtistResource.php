<?php

namespace App\Http\Resources;

use App\Models\Genre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtistResource extends JsonResource
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
            "name" => $this->name,
            "monthly_listeners" => $this->monthly_listeners,
            "genre" => $this->whenLoaded('genre',new GenreResource(Genre::findOrFail($this->genre_id))),
            "profile_pic_url" => $this->profile_pic_url
        ];
    }
}
