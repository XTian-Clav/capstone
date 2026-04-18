<?php

namespace App\Notifications;

use App\Models\Startup;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Filament\Notifications\Notification as FilamentNotification;

class StartupApproved extends Notification
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
            ->line("We are pleased to inform you that your startup proposal, **{$this->startup->startup_name}**, has been officially reviewed.")
            ->line("Status: **Approved**")
            ->line('Congratulations! Your proposal has been accepted into the incubation process. We are excited to have you on board.')
            ->line('You may log in to the PITBI Portal to view your status and proceed with the next steps.')
            ->line("")
            ->action('Login to PITBI Portal', 'https://pitbiportal.site')
            ->salutation("Best regards, \n**PITBI Admin**");
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->success()
            ->color('success')
            ->title('Startup Proposal Approved')
            ->body("Your startup proposal entitled <strong>{$this->startup->startup_name}</strong> has been approved.")
            ->getDatabaseMessage();
    }
}
