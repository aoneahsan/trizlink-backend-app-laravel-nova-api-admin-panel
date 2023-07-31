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
                'username' => $this->user->name,
                'email' => $this->user->email,
                'profilePitcher' => $this->user->profilePitcher,
            ] : null,

            'sortOrderNo' => $this->sortOrderNo,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at->diffForHumans(),
            'updatedAt' => $this->updated_at->diffForHumans(),
        ];
    }
}
