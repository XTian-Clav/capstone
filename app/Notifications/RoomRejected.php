<?php

namespace App\Notifications;

use App\Models\ReserveRoom;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Filament\Notifications\Notification as FilamentNotification;

class RoomRejected extends Notification
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
            ->subject('PITBI Portal Update: Room Reservation Status')
            ->greeting('Good Day ' . ($notifiable->name ?? 'Incubatee') . '!')
            ->line("Regarding your reservation for **{$this->RoomName}**:")
            ->line("Status: **Rejected**")
            ->line("Reason for Rejection:")
            ->line('"' . ($this->reservation->admin_comment ?? 'No specific reason provided.') . '"')
            ->line("The room may be unavailable due to a prior booking or maintenance. Please check the calendar for an alternative schedule.")
            ->line("")
            ->action('Login to PITBI Portal', 'https://pitbiportal.site')
            ->salutation("Best regards, \n**PITBI Admin**");
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->success()
            ->color('danger')
            ->title('Room Reservation Rejected')
            ->body("Your reservation for <strong>{$this->RoomName}</strong> has been rejected.")
            ->getDatabaseMessage();
    }
}
