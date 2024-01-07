<?php

namespace App\Http\Resources\Zaions\ZLink\Plans;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanLimitResource extends JsonResource
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
            'version' => $this->version,
            'displayName' => $this->displayName,
            'name' => $this->name,
            'maxLimit' => $this->maxLimit,
            'timeLine' => $this->timeLine,
            'description' => $this->description,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
        ];
    }
}
