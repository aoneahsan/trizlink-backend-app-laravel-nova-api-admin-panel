<?php

namespace App\Http\Resources\Zaions\ZLink\LinkInBios;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LibPredefinedDataResource extends JsonResource
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
            'type' => $this->type,
            'title' => $this->title,
            'icon' => $this->icon,
            'background' => $this->background,
            'preDefinedDataType' =>
            $this->preDefinedDataType,
            'isActive' => $this->isActive,
            'sortOrderNo' => $this->sortOrderNo,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
