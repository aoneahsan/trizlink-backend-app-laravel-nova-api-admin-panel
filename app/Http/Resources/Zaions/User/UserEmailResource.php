<?php

namespace App\Http\Resources\Zaions\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserEmailResource extends JsonResource
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
            'email' => $this->email,
            'status' => $this->status,
            'isDefault' => $this->isDefault,
            'isPrimary' => $this->isPrimary,
            'optExpireTime' => $this->optExpireTime,
            'verifiedAt' => $this->verifiedAt ? $this->verifiedAt->diffForHumans() : null,

            'sortOrderNo' => $this->sortOrderNo,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at->diffForHumans(),
            'updatedAt' => $this->updated_at->diffForHumans()
        ];
    }
}
