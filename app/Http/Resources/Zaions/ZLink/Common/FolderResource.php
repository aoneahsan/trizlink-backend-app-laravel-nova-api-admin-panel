<?php

namespace App\Http\Resources\Zaions\ZLink\Common;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FolderResource extends JsonResource
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
            'title' => $this->title,
            'icon' => $this->icon,
            'isStared' => $this->isStared,
            'isHidden' => $this->isHidden,
            'isFavorite' => $this->isFavorite,
            'isPasswordProtected' => $this->isPasswordProtected,
            'password' => $this->password,
            'folderForModel' => $this->folderForModel,
            'isDefault' => $this->isDefault,
            'sortOrderNo' => $this->sortOrderNo,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
