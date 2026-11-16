<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlaylistResource extends JsonResource
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
            "public" => $this->public,
            "picture_url" => $this->picture_url,
            "created_at" => $this->created_at,
            "user" => $this->whenLoaded("users", new UserResource(User::findOrFail($this->user_id))),
            "songs" => $this->songs,
        ];
    }
}
