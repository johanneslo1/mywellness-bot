<?php

namespace App\Notifications;

use App\Models\SearchRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use NotificationChannels\Telegram\TelegramMessage;

class SearchRequestWasSuccessfulNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(protected SearchRequest $searchRequest, protected Collection $daysInCommingWeek)
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail']; // 'telegram'
    }

    public function toTelegram($notifiable): TelegramMessage
    {
        $message = TelegramMessage::create()
            // Optional recipient user id.
            ->to($notifiable->routeNotificationForTelegram())
            // Markdown supported.
            ->content("Ein passender Zeitslot wurde gefunden: \n\n");

        foreach ($this->daysInCommingWeek as $day) {
            $text = $day['date']->format('d.m.Y (D)');
            $message->line($text);
        }

        return $message->button('Buchen', $this->daysInCommingWeek[0]['url']);
    }

    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject('Ein Tag für deine Auszeit ist gefunden!')
            ->markdown('emails.search-request-was-successful', [
                'daysInCommingWeek' => $this->daysInCommingWeek,
                'searchRequest'     => $this->searchRequest,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
