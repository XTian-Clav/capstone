<?php

namespace App\Notifications;

use App\Models\ReserveRoom;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Filament\Notifications\Notification as FilamentNotification;

class RoomApproved extends Notification
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
            ->line("Your request for **{$this->RoomName}** has been **approved**.")
            ->line("The room is now scheduled for your use. Please ensure you follow the facility guidelines and coordinate with the PITBI staff for access on your scheduled date.")
            ->line("")
            ->action('Login to PITBI Portal', 'https://pitbiportal.site')
            ->salutation("Best regards, \n**PITBI Admin**");
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->color('success')
            ->iconColor('sucess')
            ->icon('heroicon-m-check-circle')
            ->title('Room Reservation Approved')
            ->body("Your reservation for <strong>{$this->RoomName}</strong> has been approved.")
            ->getDatabaseMessage();
    }
}
