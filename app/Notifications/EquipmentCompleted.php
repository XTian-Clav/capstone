<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\ReserveEquipment;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Filament\Notifications\Notification as FilamentNotification;

class EquipmentCompleted extends Notification
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
            ->subject('PITBI Portal Update: Equipment Reservation Completed')
            ->greeting('Good Day ' . ($notifiable->name ?? 'Incubatee') . '!')
            ->line("Your request for **{$this->EquipmentName}** has been marked completed by the Admin.")
            ->line("We hope the equipment was instrumental in your operations. Please ensure that the items have been returned in good condition to maintain our shared resources for all startups.")
            ->line("Thank you for using the PITBI services.")
            ->salutation("Best regards, \n**PITBI Admin**");
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->color('info')
            ->iconColor('info')
            ->icon('heroicon-m-check-badge')
            ->title('Equipment Reservation Completed')
            ->body("Your reservation for <strong>{$this->EquipmentName}</strong> has been marked completed completed by the Admin.")
            ->getDatabaseMessage();
    }
}
