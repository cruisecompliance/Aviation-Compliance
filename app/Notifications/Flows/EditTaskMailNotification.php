<?php

namespace App\Notifications\Flows;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class EditTaskMailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $rule_reference; // task - rule_reference
    private $editor_name; // user name (who changed task)
    private $link; // link to email template (link or deeplink for MS Teams)

    /**
     * Create a new notification instance.
     *
     * @param string $rule_reference
     * @param string $editor_name
     * @param string $link
     */
    public function __construct(string $rule_reference, string $editor_name, string $link)
    {
//        $this->delay = now()->addSeconds(2);
        $this->queue = 'mail';

        $this->rule_reference = $rule_reference;
        $this->editor_name = $editor_name;
        $this->link = $link;

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
            ->greeting("$this->rule_reference was update successfully.")
            ->line("The user $this->editor_name has changed the Rule Reference.")
            ->action('View', $this->link)
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
