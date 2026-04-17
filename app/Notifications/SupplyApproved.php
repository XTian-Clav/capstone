<?php

namespace App\Notifications;

use App\Models\ReserveSupply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Filament\Notifications\Notification as FilamentNotification;

class SupplyApproved extends Notification
{
    use Queueable;

    public string $SupplyName;

    public function __construct(public ReserveSupply $reservation) 
    {
        $name = $this->reservation->supply?->item_name ?? 'Supply';
        $qty = $this->reservation->quantity ?? 1;

        $this->SupplyName = "{$name} ({$qty})";
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
            ->line("Your request for **{$this->SupplyName}** has been **approved**.")
            ->line("You may now collect the items at the PITBI office.Please be reminded that these are consumable supplies provided under a replacement policy—ensure that used items are replaced with the same brand or quality upon the conclusion of your usage.")
            ->line("")
            ->action('Login to PITBI Portal', 'https://pitbiportal.site')
            ->salutation("Best regards, **PITBI Admin**");
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->success()
            ->color('success')
            ->title('Supply Reservation Approved')
            ->body("Your reservation for <strong>{$this->SupplyName}</strong> has been approved.")
            ->getDatabaseMessage();
    }
}
