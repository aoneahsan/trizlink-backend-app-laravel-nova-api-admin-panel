<?php

namespace App\Http\Resources\Zaions\ZLink\Analytics;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PixelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uniqueId,
            'platform' => $this->platform,
            'title' => $this->title,
            'pixelId' => $this->pixelId,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createAt' => $this->created_at->diffForHumans(),
            'updatedAt' => $this->updated_at->diffForHumans()
        ];
    }
}
