<?php

namespace App\Notifications\Flows;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsChannel;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsMessage;

class EditTaskTeamsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $rule_reference;
    private $editor_name; // user name who changed task

    /**
     * Create a new notification instance.
     *
     * @param string $rule_reference
     * @param string $editor_name
     */
    public function __construct(string $rule_reference, string $editor_name)
    {
//        $this->delay = now()->addSeconds(2);
//        $this->queue = '';

        $this->rule_reference = $rule_reference;
        $this->editor_name = $editor_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [MicrosoftTeamsChannel::class];
    }


    /**
     * @param $notifiable
     * @return MicrosoftTeamsMessage
     * @throws \NotificationChannels\MicrosoftTeams\Exceptions\CouldNotSendNotification
     */
    public function toMicrosoftTeams($notifiable)
    {
        //  Available Message methods
        // to(string $webhookUrl): Recipient's webhook url.
        // title(string $title): Title of the message.
        // summary(string $summary): Summary of the message.
        // type(string $type): Type which is used as theme color (any valid hex code or one of: primary|secondary|accent|error|info|success|warning).
        // content(string $content): Content of the message (Markdown supported).
        // button(string $text, string $url = '', array $params = []): Text and url of a button. Wrapper for an potential action.
        // action(string $text, $type = 'OpenUri', array $params = []): Text and type for a potential action. Further params can be added depending on the action. For more infos about different types check out this link.
        // options(array $options, $sectionId = null): Add additional options to pass to the message payload object.

        return MicrosoftTeamsMessage::create()
            ->to(config('services.teams.webhook_url'))
            ->type('success')
            ->title("$this->rule_reference was update successfully.")
            ->content("The user $this->editor_name  has changed the Rule Reference.")
            ->button('View', url("/user/flows#$this->rule_reference"));
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
