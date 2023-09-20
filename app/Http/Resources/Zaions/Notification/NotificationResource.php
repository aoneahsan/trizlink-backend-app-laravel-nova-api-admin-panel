<?php

namespace App\Http\Resources\Zaions\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ZLInviteeId' => $this->ZLInviteeId,
            'zlNotificationType' => $this->zlNotificationType,
            'data' => $this->data,
            'notifiable_id' => $this->notifiable_id,
            'read_at' => $this->read_at,
            'createdAt' => $this->created_at->diffForHumans(),
            'updatedAt' => $this->updated_at->diffForHumans(),
        ];
    }
}
