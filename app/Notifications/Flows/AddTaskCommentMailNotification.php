<?php

namespace App\Notifications\Flows;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddTaskCommentMailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $rule_reference;
    private $comment_author;
    private $message;

    /**
     * Create a new notification instance.
     *
     * @param string $rule_reference
     * @param string $comment_author
     * @param string $message
     */
    public function __construct(string $rule_reference, string $comment_author, string $message)
    {
//        $this->delay = now()->addSeconds(2);
        $this->queue = 'mail';

        $this->rule_reference = $rule_reference;
        $this->comment_author = $comment_author;
        $this->message = $message;
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
            ->subject("New comment: $this->rule_reference")
            ->greeting("New comment: $this->rule_reference")
            ->line("$this->comment_author added comments.")
            ->line("$this->message")
            ->action('View', url("/user/flows#$this->rule_reference"))
            ->line('Thank you for using our application!');
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
