<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\ReserveEquipment;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Filament\Notifications\Notification as FilamentNotification;

class EquipmentApproved extends Notification
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
            ->line("Your request for **{$this->EquipmentName}** has been **approved**.")
            ->line("Please proceed to the PITBI office to claim the equipment. We kindly remind you to adhere to the equipment usage guidelines and coordinate with the PITBI Staff for the turnover process.")
            ->line("")
            ->action('Login to PITBI Portal', 'https://pitbiportal.site')
            ->salutation("Best regards, **PITBI Admin**");
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->success()
            ->color('success')
            ->title('Equipment Reservation Approved')
            ->body("Your reservation for <strong>{$this->EquipmentName}</strong> has been approved.")
            ->getDatabaseMessage();
    }
}
