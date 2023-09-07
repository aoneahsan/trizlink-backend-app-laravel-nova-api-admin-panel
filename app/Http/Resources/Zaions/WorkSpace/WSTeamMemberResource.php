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
            // 'wilToken' => $this->wilToken,
            'invitedAt' => $this->invitedAt->diffForHumans(),
            'inviteAcceptedAt' => $this->inviteAcceptedAt !== null ? $this->inviteAcceptedAt->diffForHumans() : null,
            'inviteRejectedAt' => $this->inviteRejectedAt !== null ? $this->inviteRejectedAt->diffForHumans() : null,
            'accountStatusUpdaterRemarks' => $this->accountStatusUpdaterRemarks,
            'accountStatusLastUpdatedBy' => $this->accountStatusLastUpdatedBy,
            'resendAllowedAfter' => $this->resendAllowedAfter,
            'memberRole' => $this->memberRole ? [
                'id' => $this->memberRole->id,
                'name' => $this->memberRole->name,
            ] : null,
            'team' => $this->workspaceTeam ? [
                'id' => $this->workspaceTeam->uniqueId,
                'title' => $this->workspaceTeam->title,
            ] : null,
            'sortOrderNo' => $this->sortOrderNo,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at->diffForHumans(),
            'updatedAt' => $this->updated_at->diffForHumans(),
        ];
    }
}
