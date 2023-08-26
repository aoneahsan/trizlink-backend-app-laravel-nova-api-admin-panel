<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use Illuminate\Notifications\DatabaseNotification;

class ZDatabaseChannel extends IlluminateDatabaseChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function send($notifiable, Notification $notification)
    {
        $data = $this->getData($notifiable, $notification);
        $zlNotificationType = null;
        $ZLInviteeId = null;
        if (isset($data['zlNotificationType'])) {
            $zlNotificationType = $data['zlNotificationType'];
            unset($data['zlNotificationType']);
        }
        if (isset($data['ZLInviteeId'])) {
            // if user invite someone to join team then a notification send to invitee to show invite notification to that user I am using this column ZLInviteeId.
            $ZLInviteeId = $data['ZLInviteeId'];
            unset($data['ZLInviteeId']);
        }

        return $notifiable->routeNotificationFor('database')->create([
            'id'      => $notification->id,
            'type'    => get_class($notification),
            'zlNotificationType' => $zlNotificationType ?? null,
            'zlInviteeId' => $ZLInviteeId ?? null,
            // 'zlinkBackendOnly' => $zlinkBackendOnly ?? null,
            'data'    => $data,
            'read_at' => null,
        ]);
    }
}
