<?php

namespace App\Notifications\Flows;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class EditTaskMailNotification extends Notification
{
    use Queueable;

    private $rule_reference;

    /**
     * Create a new notification instance.
     *
     * @param string $rule_reference
     */
    public function __construct(string $rule_reference)
    {
        $this->rule_reference = $rule_reference;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        return (new MailMessage)
            ->greeting("{$this->rule_reference} was update successfully.")
            ->line('The user ' . Auth::user()->name . ' has changed the Rule Reference.')
            ->action('View', url('/user/flows'))
            ->line('Thank you for using our app');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
