<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'thumbnail' => $this->thumbnail,
            'url_video' => $this->url_video,
            'description' => $this->description,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'photos' => ContentPhotoResource::collection($this->whenLoaded('photos')),
            'tutorials' => ContentTutorialResource::collection($this->whenLoaded('tutorials')),
        ];
    }
}