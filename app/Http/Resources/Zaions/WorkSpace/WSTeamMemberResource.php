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
            'workspace' => $this->workspace ? [
                'workspaceId' => $this->workspace ? $this->workspace['uniqueId'] : null,
                'workspaceName' => $this->workspace ? $this->workspace['title'] : null,
                'workspaceTimezone' => $this->workspace ? $this->workspace['timezone'] : null,
                'workspaceData' => $this->workspace ? $this->workspace['workspaceData'] : null,
                'workspaceImage' => $this->workspace ? $this->workspace['workspaceImage'] : null, 'createdAt' => $this->created_at->diffForHumans(),
                'updatedAt' => $this->updated_at->diffForHumans(),
            ] : null,
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
            'extraAttributes' => $this->extraAttributes, 'formattedCreatedAt' => $this->created_at->diffForHumans(),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at->diffForHumans(),
        ];
    }
}
