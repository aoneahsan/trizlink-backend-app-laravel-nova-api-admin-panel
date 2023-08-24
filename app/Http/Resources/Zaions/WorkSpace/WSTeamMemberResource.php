<?php

namespace App\Http\Resources\Zaions\Workspace;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WSTeamMemberResource extends JsonResource
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
            'accountStatus' => $this->accountStatus,
            'invitedAt' => $this->invitedAt,
            'inviteAcceptedAt' => $this->inviteAcceptedAt,
            'accountStatusUpdaterRemarks' => $this->accountStatusUpdaterRemarks,
            'accountStatusLastUpdatedBy' => $this->accountStatusLastUpdatedBy,

            'memberRole' => $this->memberRole ? [
                'id' => $this->memberRole->id,
                'name' => $this->memberRole->name,
            ] : null,

            'team' => $this->workspaceTeam ? [
                'id' => $this->workspaceTeam->uniqueId,
                'title' => $this->workspaceTeam->title,
            ] : $this->teamId,

            'sortOrderNo' => $this->sortOrderNo,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at->diffForHumans(),
            'updatedAt' => $this->updated_at->diffForHumans(),
        ];
    }
}
