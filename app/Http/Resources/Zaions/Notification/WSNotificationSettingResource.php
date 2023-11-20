<?php

namespace App\Http\Resources\Zaions\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WSNotificationSettingResource extends JsonResource
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

            'notificationOnProfile' => $this->notificationOnProfile ? $this->notificationOnProfile : null,
            'allowPushNotification' => $this->allowPushNotification ? $this->allowPushNotification : null,
            'type' => $this->type ? $this->type : null,

            'sortOrderNo' => $this->sortOrderNo,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at->diffForHumans(),
            'updatedAt' => $this->updated_at->diffForHumans(),
        ];
    }
}
