<?php

namespace App\Http\Resources\Zaions\WorkSpace;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SharedWSResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uniqueId ? $this->uniqueId : null,
            'accountStatus' => $this->accountStatus,
            'user' => $this->user,
            'workspaceId' => $this->workspace ? $this->workspace['uniqueId'] : null,
            'workspaceName' => $this->workspace ? $this->workspace['title'] : null,
            'workspaceTimezone' => $this->workspace ? $this->workspace['timezone'] : null,
            'workspaceData' => $this->workspace ? $this->workspace['workspaceData'] : null,
            'workspaceImage' => $this->workspace ? $this->workspace['workspaceImage'] : null,
            'isFavorite' => $this->isFavorite,
            'createdAt' => $this->created_at->diffForHumans(),
            'updatedAt' => $this->updated_at->diffForHumans(),
        ];
    }
}
