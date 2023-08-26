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
        $zlNotificationType = $data['zlNotificationType'];
        unset($data['zlNotificationType']);
        // $zlinkBackendOnly = $data['zlinkBackendOnly'];
        // unset($data['zlinkBackendOnly']);
        // dd($data, $zlNotificationType);
        return $notifiable->routeNotificationFor('database')->create([
            'id'      => $notification->id,
            'type'    => get_class($notification),
            'zlNotificationType' => $zlNotificationType ?? null,
            // 'zlinkBackendOnly' => $zlinkBackendOnly ?? null,
            'data'    => $data,
            'read_at' => null,
        ]);
    }
}