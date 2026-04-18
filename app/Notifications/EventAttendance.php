<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Filament\Notifications\Notification as FilamentNotification;

class EventAttendance extends Notification
{
    use Queueable;

    public function __construct(
        public Event $event,
        public bool $isAttending
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $start = $this->event->start_date?->format('F d, Y h:i A') ?? 'TBA';
        $end = $this->event->end_date?->format('F d, Y h:i A') ?? 'TBA';
        $location = $this->event->location ?? 'SIP Building, Palawan State University-Main Campus, Puerto Princesa City';

        $message = (new MailMessage)
            ->subject("Attendance Record: {$this->event->event}")
            ->greeting("Hello " . ($notifiable->name ?? 'User') . "!");

        if ($this->isAttending) {
            $message->success()
                ->line("Your attendance for the following event has been successfully recorded:")
                ->line("**Event:** {$this->event->event}")
                ->line("**Start Date:** {$start}")
                ->line("**End Date:** {$end}")
                ->line("**Location:** {$location}")
                ->line("We have updated our records, and we look forward to your participation.");
        } else {
            $message->error()
                ->line("You have opted **not to attend** the following event:")
                ->line("**Event:** {$this->event->event}")
                ->line("**Schedule:** {$start} - {$end}")
                ->line("**Location:** {$location}")
                ->line("If this was a mistake, please contact the PITBI Administration immediately. Please note that attendance responses are intended to be final for logistical purposes.");
        }

        return $message
            ->action('Login to PITBI Portal', 'https://pitbiportal.site')
            ->line("Thank you for using the PITBI Portal.")
            ->salutation("Best regards, \n**PITBI Admin**");
    }

    public function toDatabase(object $notifiable): array
    {
        $status = $this->isAttending ? 'success' : 'danger';
        $response = $this->isAttending ? 'Attending' : 'Not Attending';
        
        $start = $this->event->start_date?->format('M d, Y h:i A') ?? 'TBA';
        $end = $this->event->end_date?->format('M d, Y h:i A') ?? 'TBA';
        $location = $this->event->location ?? 'SIP Building, Palawan State University-Main Campus, Puerto Princesa City';

        return FilamentNotification::make()
            ->title($this->isAttending ? 'Attendance Confirmed' : 'Absence Recorded')
            ->icon($this->isAttending ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle')
            ->status($status)
            ->color($status)
            ->body("
                <strong>Event:</strong> {$this->event->event}<br>
                <strong>Start Date:</strong> {$start}<br>
                <strong>End Date:</strong> {$end}<br>
                <strong>Location:</strong> {$location}<br>
                <strong>Status:</strong> {$response}<br><br>
                Your response has been logged, and a copy has been sent to your email.
            ")
            ->getDatabaseMessage();
    }
}