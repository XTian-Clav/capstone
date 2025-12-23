<?php

namespace App\Filament\Portal\Pages;

use UnitEnum;
use App\Models\Event;
use Filament\Pages\Page;
use Filament\Actions\Action;

class EventReport extends Page
{
    protected static ?int $navigationSort = 4;
    protected static string|UnitEnum|null $navigationGroup = 'Generate Reports';

    protected string $view = 'filament.portal.pages.event-report';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'super_admin']);
    }

    public $events;
    public $totalEventsMonth = 0;
    public $completedEventsMonth = 0;
    public $totalCancelled = 0;
    public $avgAttendanceRate = 0;
    public $loyalParticipantsCount = 0;
    public $noShowGap = 0;
    public $topEvent;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print_report')
                ->label('Print')
                ->icon('heroicon-s-printer')
                ->color('info')
                ->url(route('EventReport'))
                ->openUrlInNewTab(),
        ];
    }

    public function mount(): void
    {
        $now = now();

        $events = Event::with('attendees')
            ->whereYear('start_date', $now->year)
            ->whereMonth('start_date', $now->month)
            ->orderByDesc('start_date')
            ->get();

        $this->totalCancelled = $events->where('status', 'Cancelled')->count();
        $this->totalEventsMonth = $events->whereIn('status', ['Upcoming', 'Ongoing', 'Completed'])->count();

        $completedEvents = $events->where('status', 'Completed');
        $this->completedEventsMonth = $completedEvents->count();

        $totalParticipants = 0;
        $totalAttended = 0;
        $attendedUserIds = collect();

        $this->events = $completedEvents->values()->transform(function ($event) use (
            &$totalParticipants,
            &$totalAttended,
            &$attendedUserIds,
        ) {
            $totalRegistrations = $event->attendees->count();
            $attendedCount = $event->attendees->where('pivot.is_attending', true)->count();
            $declinedCount = $event->attendees->where('pivot.is_attending', false)->count();

            $event->total_registrations = $totalRegistrations;
            $event->attended_count = $attendedCount;
            $event->declined_count = $declinedCount;

            $event->attendance_rate = $totalRegistrations > 0
                ? round(($attendedCount / $totalRegistrations) * 100, 1)
                : 0;

            $totalParticipants += $totalRegistrations;
            $totalAttended += $attendedCount;
            $attendedUserIds->push(...$event->attendees
                ->where('pivot.is_attending', true)
                ->pluck('id'));

            return $event;
        });

        $this->avgAttendanceRate = $totalParticipants > 0
            ? round(($totalAttended / $totalParticipants) * 100, 1)
            : 0;

        $this->noShowGap = $totalParticipants - $totalAttended;

        $this->topEvent = $this->events->sortByDesc('attended_count')->first();

        $this->loyalParticipantsCount = $attendedUserIds
            ->countBy()
            ->filter(fn($count) => $count > 1)
            ->count();
    }
}
