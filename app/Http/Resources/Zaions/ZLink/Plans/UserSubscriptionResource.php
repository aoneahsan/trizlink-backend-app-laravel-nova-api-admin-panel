<?php

namespace App\Http\Resources\Zaions\ZLink\Plans;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSubscriptionResource extends JsonResource
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
            'name' => $this->plan['name'] ? $this->plan['name'] : null,
            'startedAt' => $this->startedAt,
            'endedAt' => $this->endedAt,
            'amount' => $this->amount,
            'duration' => $this->duration,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at->diffForHumans(),
            'updatedAt' => $this->updated_at->diffForHumans()
        ];
    }
}
