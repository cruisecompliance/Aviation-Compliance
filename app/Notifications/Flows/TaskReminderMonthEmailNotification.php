<?php

namespace App\Notifications\Flows;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskReminderMonthEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $rule_reference;
    private $month_quarter;
    private $recipient_name;
    private $link;

    /**
     * Create a new notification instance.
     *
     * @param string $rule_reference
     * @param string $month_quarter
     * @param string $recipient_name
     * @param string $link
     */
    public function __construct(string $rule_reference, string $month_quarter, string $recipient_name, string $link)
    {
        $this->queue = 'mail';

        $this->rule_reference = $rule_reference;
        $this->month_quarter = $month_quarter;
        $this->recipient_name = $recipient_name;
        $this->link = $link;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Task Reminder Notification')
            ->greeting("Hi, $this->recipient_name")
            ->line("The month/quarter date for $this->rule_reference expires on $this->month_quarter")
            ->action('View', $this->link)
            ->line('Thank you for using our application!');

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
