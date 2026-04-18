<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\ReserveEquipment;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Filament\Notifications\Notification as FilamentNotification;

class EquipmentRejected extends Notification
{
    use Queueable;

    public string $EquipmentName;

    public function __construct(public ReserveEquipment $reservation) 
    {
        $name = $this->reservation->equipment?->equipment_name ?? 'Equipment';
        $qty = $this->reservation->quantity ?? 1;

        $this->EquipmentName = "{$name} - Qty: {$qty}";
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->success()
            ->subject('PITBI Portal Update: Equipment Reservation Status')
            ->greeting('Good Day ' . ($notifiable->name ?? 'Incubatee') . '!')
            ->line("Regarding your request for **{$this->EquipmentName}**:")
            ->line("Status: **Rejected**")
            ->line("Reason for Rejection:")
            ->line('"' . ($this->reservation->admin_comment ?? 'No specific reason provided.') . '"')
            ->line("You may review the available inventory or coordinate with the PITBI Staff for alternative tools.")
            ->line("")
            ->action('Login to PITBI Portal', 'https://pitbiportal.site')
            ->salutation("Best regards, \n**PITBI Admin**");
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->danger()
            ->color('danger')
            ->title('Equipment Reservation Rejected')
            ->body("Your reservation for <strong>{$this->EquipmentName}</strong> has been rejected.")
            ->getDatabaseMessage();
    }
}
