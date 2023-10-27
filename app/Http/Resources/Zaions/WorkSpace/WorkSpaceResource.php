<?php

namespace App\Http\Resources\Zaions\WorkSpace;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkSpaceResource extends JsonResource
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

            'workspaceName' => $this->title,
            'workspaceTimezone' => $this->timezone,
            'workspaceData' => $this->workspaceData,
            'workspaceImage' => $this->workspaceImage,
            'internalPost' =>  $this->internalPost,
            'user' => $this->user ? [
                'id' => $this->user->uniqueId,
                'username' => $this->user->username,
                'email' => $this->user->email,
                'avatar' => $this->user->avatar,
                'lastSeenAt' => $this->user && $this->user->lastSeenAt ? $this->user->lastSeenAt : null,
                'lastSeenAtFormatted' => $this->user && $this->user->lastSeenAt ? $this->user->lastSeenAt->diffForHumans() : null,
            ] : null,

            'sortOrderNo' => $this->sortOrderNo,
            'isActive' => $this->isActive,
            'isFavorite' => $this->isFavorite,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at->diffForHumans(),
            'updatedAt' => $this->updated_at->diffForHumans(),
        ];
    }
}
