<?php

namespace App\Notifications\Flows;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskReminderDueDateEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $rule_reference;
    private $due_date;
    private $user_name;
    private $link;

    /**
     * Create a new notification instance.
     *
     * @param string $rule_reference
     * @param string $due_date
     * @param string $user_name
     * @param string $link
     */
    public function __construct(string $rule_reference, string $due_date, string $user_name, string $link)
    {
//        $this->delay = now()->addSeconds(2);
        $this->queue = 'mail';

        $this->rule_reference = $rule_reference;
        $this->due_date = $due_date;
        $this->user_name = $user_name;
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
            ->subject('Task Reminder Notification')
            ->greeting("Hi, $this->user_name")
            ->line("The due-date for $this->rule_reference expires on $this->due_date")
            ->action('View', $this->link)
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
