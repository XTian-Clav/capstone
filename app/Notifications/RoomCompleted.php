<?php

namespace App\Notifications;

use App\Models\ReserveRoom;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Filament\Notifications\Notification as FilamentNotification;

class RoomCompleted extends Notification
{
    use Queueable;

    public string $RoomName;

    public function __construct(public ReserveRoom $reservation) 
    {
        $this->RoomName = $reservation->room?->room_type ?? 'Equipment';
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->success()
            ->subject('PITBI Portal Update: Room Reservation Completed')
            ->greeting('Good Day ' . ($notifiable->name ?? 'Incubatee') . '!')
            ->line("We hope the facility provided a productive environment for your activities. We look forward to hosting your next session or meeting soon.")
            ->line("Thank you for using the PITBI services.")
            ->line("")
            ->action('Login to PITBI Portal', 'https://pitbiportal.site')
            ->salutation("Best regards, **PITBI Admin**");
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->success()
            ->color('cyan')
            ->title('Room Reservation Completed')
            ->body("Your reservation for <strong>{$this->RoomName}</strong> has been marked completed.")
            ->getDatabaseMessage();
    }
}
