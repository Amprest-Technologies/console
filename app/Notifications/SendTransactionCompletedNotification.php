<?php

namespace App\Notifications;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class SendTransactionCompletedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        public array $payload, public ?Project $project = null
    ){}

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the slack representation of the notification.
     *
     * @param $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     * @author Alvin G. Kaburu <geekaburu@amprest.co.ke>
     */
    public function toSlack($notifiable): SlackMessage
    {
        //  Get the title
        $title = $this->project->name ?? 'Unregistered Project';

        //  Format the payload
        $payload = array_merge($this->payload, [
            'TransTime' => Carbon::parse($this->payload['TransTime'])->format('Y-m-d H:i:s')
        ]);

        //  Get the project
        return (new SlackMessage)
            ->success()
            ->content('A new payment has been received from the Amprest Pay Portal.')
            ->attachment(function ($attachment) use ($title, $payload) {
                $attachment->title($title)->fields($payload);
            });
    }
}
