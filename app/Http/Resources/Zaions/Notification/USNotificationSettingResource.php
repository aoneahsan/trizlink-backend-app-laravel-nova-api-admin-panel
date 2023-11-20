<?php

namespace App\Http\Resources\Zaions\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class USNotificationSettingResource extends JsonResource
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

            'invitationNotification' => $this->invitationNotification ? $this->invitationNotification : null,
            'newDeviceLogin' => $this->newDeviceLogin ? $this->newDeviceLogin : null,
            'passwordReset' => $this->passwordReset ? $this->passwordReset : null,
            'otherNotification' => $this->otherNotification ? $this->otherNotification : null,
            'browser' => $this->browser ? $this->browser : null,
            'email' => $this->email ? $this->email : null,
            'sms' => $this->sms ? $this->sms : null,

            'sortOrderNo' => $this->sortOrderNo,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at->diffForHumans(),
            'updatedAt' => $this->updated_at->diffForHumans(),
        ];
    }
}
