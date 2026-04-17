<?php

namespace App\Notifications;

use App\Models\ReserveSupply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Filament\Notifications\Notification as FilamentNotification;

class SupplyCompleted extends Notification
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
            ->subject('PITBI Portal Update: Supply Reservation Completed')
            ->greeting('Good Day ' . ($notifiable->name ?? 'Incubatee') . '!')
            ->line("We are glad to support your resource needs. As a reminder, please ensure the consumable items are replaced as per the PITBI's policy to keep the inventory ready for the next incubatee.")
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
            ->title('Supply Reservation Completed')
            ->body("Your reservation for <strong>{$this->SupplyName}</strong> has been marked completed.")
            ->getDatabaseMessage();
    }
}
