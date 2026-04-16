<?php

namespace App\Notifications;

use App\Models\Startup;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Filament\Notifications\Notification as FilamentNotification;
use App\Filament\Portal\Resources\Startups\Pages\ViewStartup;

class StartupApprovedNotification extends Notification
{
    public function __construct(public Startup $startup) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('PITBI: Startup Proposal Approval')
            ->greeting('Good Day ' . ($notifiable->name ?? 'Founder') . '!')
            ->line("We are pleased to inform you that your startup proposal entitled **{$this->startup->startup_name}** has been officially approved.")
            ->line('You may now log in to the portal to view your status and proceed with the next steps of the incubation process.')
            ->salutation('Regards, The PITBI Team');
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