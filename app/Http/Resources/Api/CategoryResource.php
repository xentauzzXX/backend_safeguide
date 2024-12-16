<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
// use App\Filament\Resources\ContentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'image' => $this->image,
            'contents_count' => $this->contents_count,
            // 'contents' => ContentResource::collection($this->whenLoaded('contents')),
        ];
    }
}