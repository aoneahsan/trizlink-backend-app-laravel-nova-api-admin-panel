<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    use Queueable;

    private $anyData1 = null;
    private $anyData2 = null;

    /**
     * Create a new notification instance.
     */
    public function __construct($data1, $data2)
    {
        $this->afterCommit();
        $this->anyData1 = $data1;
        $this->anyData2 = $data2;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', \Laravel\Nova\Notifications\NovaChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('Hello from ZLink Backend :) - Data1: ' . $this->anyData1)
            ->line('One of your invoices has been paid!')
            // ->lineIf($this->amount > 0, "Amount paid: {$this->amount}") // can be any condition
            ->action('View Zaions Website', 'https://zaions.com')
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

    // public function toDatabase(object $notifiable): array
    // {
    //     return [
    //         'ldhf' => 'asd',
    //         'anyData1' => $this->anyData1,
    //         'anyData2' => $this->anyData2,
    //         'dslfkjf' => [
    //             'df' => '44',
    //             'sdfsf' => [
    //                 0 => 21312,
    //                 1 => true,
    //                 'sdf0' => [
    //                     'sdfsd' => 0001
    //                 ]
    //             ]
    //         ]
    //     ];
    // }

    public function toDatabase(object $notifiable): array
    {
        return [
            'myFirstColumn' => $this->anyData1,
            'mySecondColumn' => $this->anyData2,
            'myThirdColumn' => 'some text...',
        ];
    }

    /**
     * Get the nova representation of the notification
     * 
     * @return array
     */
    public function toNova()
    {
        return (new \Laravel\Nova\Notifications\NovaNotification)
            ->message('Your report is ready to download. - Data2: ' . $this->anyData2)
            ->action('Visit', \Laravel\Nova\URL::remote('https://zaions.com'))
            ->icon('eye')
            ->type('info');
    }
}
