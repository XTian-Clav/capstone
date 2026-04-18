<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Filament\Notifications\Notification as FilamentNotification;

class EventAttendanceAdmin extends Notification
{
    use Queueable;

    public function __construct(
        public Event $event,
        public bool $isAttending
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusText = $this->isAttending ? 'Attending' : 'Not Attending';
        
        $message = (new MailMessage);

        if ($this->isAttending) {
            $message->success();
        } else {
            $message->error();
        }

        return $message
            ->subject("Attendance Status Updated: {$this->event->event}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("An admin has updated your attendance status for the event: **{$this->event->event}**.")
            ->line("New Status: **{$statusText}**")
            ->line("If you believe this is an error, please contact the administration.")
            ->action('Login to PITBI Portal', 'https://pitbiportal.site')
            ->salutation("Best regards, \n**PITBI Admin**");
    }

    public function toDatabase(object $notifiable): array
    {
        $statusLabel = $this->isAttending ? 'Attending' : 'Not Attending';
        $color = $this->isAttending ? 'success' : 'danger';

        return FilamentNotification::make()
            ->title('Attendance Status Updated')
            ->icon($this->isAttending ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle')
            ->body("Your status for <strong>{$this->event->event}</strong> has been changed to <strong>{$statusLabel}</strong> by Admin.")
            ->status($color)
            ->color($color)
            ->getDatabaseMessage();
    }
}