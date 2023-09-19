<?php

namespace App\Http\Resources\Zaions\ZLink\ShortLinks;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmbededWidgetResource extends JsonResource
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
            'name' => $this->name,
            'canCodeJs' => $this->canCodeJs,
            'canCodeHtml' => $this->canCodeHtml,
            'jsCode' => $this->jsCode,
            'HTMLCode' => $this->HTMLCode,
            'displayAt' => $this->displayAt,
            'delay' => $this->delay,
            'position' => $this->position,
            'animation' => $this->animation,
            'closingOption' => $this->closingOption,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
