<?php

namespace App\Http\Resources\Zaions\Zlink\ShortLinks;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SLAnalyticsResource extends JsonResource
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
            'userLocationCoords' => $this->userLocationCoords,
            'userDeviceInfo' => $this->userDeviceInfo,
            'type' => $this->type,
            'userIP' => $this->userIP,

            'sortOrderNo' => $this->sortOrderNo,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at->diffForHumans(),
            'updatedAt' => $this->updated_at->diffForHumans()
        ];
    }
}
