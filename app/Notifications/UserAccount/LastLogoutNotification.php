<?php

namespace App\Notifications\UserAccount;

use App\Zaions\Enums\NotificationTypeEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class LastLogoutNotification extends Notification
{
    use Queueable;

    private $notificationData = null;

    /**
     * Create a new notification instance.
     */
    public function __construct($_notificationData)
    {
        //
        $this->afterCommit();
        $this->notificationData = $_notificationData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'zlNotificationType' => NotificationTypeEnum::lastLogout->name,
            'item' => $this->notificationData,
        ];
    }

    // public function send($notifiable, Notification $notification)
    // {
    //     $data = $notification->toDatabase($notifiable);

    //     // set custom message in another variable and unset it from default array.
    //     $msg = $data['message_text'];
    //     unset($data['message_text']);

    //     // lets create a DB row now with our custom field message text

    //     return $notifiable->routeNotificationFor('database')->create([

    //         'id' => $notification->id,
    //         'znType' => $msg, //<-- comes from toDatabase() Method, this is my customised column
    //         'notifiable_type' => Auth::user()->id,
    //         'type' => get_class($notification),
    //         'data' => $data,
    //         'read_at' => null,
    //     ]);
    // }
}
