<?php

namespace App\Http\Resources\Zaions\Workspace;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkspaceMemberResource extends JsonResource
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
            'email' => $this->email,

            'isActive' => $this->isActive,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
