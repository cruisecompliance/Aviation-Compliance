<?php

namespace App\Notifications\Flows;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskOwnerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $rule_reference;
    private $task_owner;
    private $editor_name;
    private $link;


    /**
     * Create a new notification instance.
     *
     * @param string $rule_reference
     * @param string $task_owner
     * @param string $editor_name
     * @param string $link
     */
    public function __construct(string $rule_reference, string $task_owner, string $editor_name, string $link)
    {
        $this->queue = 'mail';

        $this->rule_reference = $rule_reference;
        $this->task_owner = $task_owner;
        $this->editor_name = $editor_name;
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
            ->subject("$this->editor_name assigned $this->rule_reference to $this->task_owner")
//            ->greeting("$this->editor_name assigned $this->rule_reference $this->task_owner")
            ->line("$this->editor_name assigned $this->rule_reference to $this->task_owner")
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
