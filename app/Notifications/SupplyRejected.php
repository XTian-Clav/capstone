<?php

namespace App\Notifications;

use App\Models\ReserveSupply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Filament\Notifications\Notification as FilamentNotification;

class SupplyRejected extends Notification
{
    use Queueable;

    public string $SupplyName;

    public function __construct(public ReserveSupply $reservation) 
    {
        $name = $this->reservation->supply?->item_name ?? 'Supply';
        $qty = $this->reservation->quantity ?? 1;

        $this->SupplyName = "{$name} - Qty: {$qty}";
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->success()
            ->subject('PITBI Portal Update: Supply Reservation Status')
            ->greeting('Good Day ' . ($notifiable->name ?? 'Incubatee') . '!')
            ->line("Regarding your request for **{$this->SupplyName}**:")
            ->line("Status: **Rejected**")
            ->line("Reason for Rejection:")
            ->line('"' . ($this->reservation->admin_comment ?? 'No specific reason provided.') . '"')
            ->line("If you have questions regarding the replacement policy or availability, please reach out to the PITBI staff.")
            ->line("")
            ->action('Login to PITBI Portal', 'https://pitbiportal.site')
            ->salutation("Best regards, \n**PITBI Admin**");
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->color('danger')
            ->iconColor('danger')
            ->icon('heroicon-m-x-circle')
            ->title('Supply Reservation Rejected')
            ->body("Your reservation for <strong>{$this->SupplyName}</strong> has been rejected.")
            ->getDatabaseMessage();
    }
}
