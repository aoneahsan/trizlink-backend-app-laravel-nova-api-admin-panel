<?php

namespace App\Http\Resources\Zaions\ZLink\LinkInBios;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LibBlockResource extends JsonResource
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
            'blockType' => $this->blockType,
            'blockContent' => $this->blockContent,

            'isActive' => $this->isActive,
            'sortOrderNo' => $this->sortOrderNo,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
