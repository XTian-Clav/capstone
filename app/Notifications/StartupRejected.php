<?php

namespace App\Notifications;

use App\Models\Startup;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Filament\Notifications\Notification as FilamentNotification;

class StartupRejected extends Notification
{
    use Queueable;

    public function __construct(public Startup $startup) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->success()
            ->subject('PITBI Portal Update: Startup Proposal Status')
            ->greeting('Good Day ' . ($notifiable->name ?? 'Incubatee') . '!')
            ->line("This is to inform you that your startup proposal, **{$this->startup->startup_name}**, has been reviewed.")
            ->line("Status: **Rejected**")
            ->line("Reason for Rejection:")
            ->line('"' . ($this->startup->admin_comment ?? 'No specific reason provided.') . '"')
            ->line('You may log in to the PITBI portal at your convenience to review your submission details.')
            ->line("")
            ->action('Login to PITBI Portal', 'https://pitbiportal.site')
            ->salutation("Best regards, **PITBI Admin**");
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->success()
            ->color('danger')
            ->title('Startup Proposal Rejected')
            ->body("Your startup proposal entitled <strong>{$this->startup->startup_name}</strong> has been rejected.")
            ->getDatabaseMessage();
    }
}
