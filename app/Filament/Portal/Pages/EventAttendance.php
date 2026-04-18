<?php

namespace App\Filament\Portal\Pages;

use App\Models\User;
use App\Models\Event;
use Filament\Pages\Page;
use App\Models\EventUser;
use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Actions\Contracts\HasActions;
use App\Notifications\EventAttendanceAdmin;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

class EventAttendance extends Page implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions;

    protected string $view = 'filament.portal.pages.event-attendance';
    protected static ?string $slug = 'event-attendance';

    public function getTitle(): string 
    {
        return "Attendance: " . ($this->event->event ?? 'Event');
    }

    public function getSubheading(): ?string
    {
        return "Manage attendance status for this event.";
    }

    public $event;
    public $search = '';
    public $attendees = [];

    public static function shouldRegisterNavigation(): bool { return false; }

    public function mount(): void
    {
        $eventId = request('event_id');
        if (! $eventId) abort(404);

        $this->event = Event::findOrFail($eventId);
        $this->loadAttendees();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back')
                ->color('gray')
                ->outlined()
                ->icon('heroicon-m-arrow-left')
                ->url(url('/portal/events')),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('search')
                ->label('')
                ->placeholder('Search attendees...')
                ->live()
                ->afterStateUpdated(fn () => $this->loadAttendees()),
        ];
    }

    public function loadAttendees(): void
    {
        $this->attendees = $this->event->attendees()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->get()
            ->map(fn($user) => [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_attending' => (int) $user->pivot->is_attending,
                'registered_at' => $user->pivot->created_at?->format('M d, Y h:i A') ?? '—',
            ])
            ->toArray();
    }

    public function updateStatusAction(): Action
    {
        return Action::make('updateStatus')
            ->badge()
            ->label(fn (array $arguments) => $this->attendees[$arguments['index']]['is_attending'] ? 'Attending' : 'Not Attending')
            ->color(fn (array $arguments) => $this->attendees[$arguments['index']]['is_attending'] ? 'success' : 'danger')
            ->icon(fn (array $arguments) => $this->attendees[$arguments['index']]['is_attending'] 
                ? 'heroicon-m-check-circle' 
                : 'heroicon-m-x-circle'
            )
            ->tooltip('Click to change status')
            ->requiresConfirmation()
            ->modalHeading('Update Attendance Status')
            ->modalDescription('Are you sure you want to change this attendee\'s status?')
            ->modalSubmitActionLabel('Yes, update it')
            ->action(function (array $arguments) {
                $index = $arguments['index'];
                $userId = $this->attendees[$index]['user_id'];
                $newStatus = ! $this->attendees[$index]['is_attending'];

                EventUser::where('event_id', $this->event->id)
                    ->where('user_id', $userId)
                    ->update(['is_attending' => $newStatus]);
                
                $user = User::find($userId);
                if ($user) {
                    $user->notify(new EventAttendanceAdmin($this->event, $newStatus));
                }

                Notification::make()
                    ->title('Status updated and user notified')
                    ->success()
                    ->send();
    
                $this->loadAttendees();
            });
    }

    public function deleteAttendeeAction(): Action
    {
        return Action::make('deleteAttendee')
            ->color('danger')
            ->icon('heroicon-m-trash')
            ->size(Size::ExtraSmall)
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                $index = $arguments['index'];
                EventUser::where('event_id', $this->event->id)
                    ->where('user_id', $this->attendees[$index]['user_id'])
                    ->delete();

                Notification::make()->title('Attendee removed')->danger()->send();
                $this->loadAttendees();
            });
    }
}