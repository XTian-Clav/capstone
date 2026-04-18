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
            ->subject('PITBI Portal Update: Room Reservation Completed')
            ->greeting('Good Day ' . ($notifiable->name ?? 'Incubatee') . '!')
            ->line("Your request for **{$this->RoomName}** has been marked completed by the Admin.")
            ->line("We hope the facility provided a productive environment for your activities. We look forward to hosting your next session or meeting soon.")
            ->line("Thank you for using the PITBI services.")
            ->salutation("Best regards, \n**PITBI Admin**");
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->color('info')
            ->iconColor('info')
            ->icon('heroicon-m-check-badge')
            ->title('Room Reservation Completed')
            ->body("Your reservation for <strong>{$this->RoomName}</strong> has been marked completed by the Admin.")
            ->getDatabaseMessage();
    }
}
