<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class EventReportController extends Controller
{
    public function EventReport(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year', now()->year);

        $events = Event::with('attendees')
            ->whereYear('start_date', $year)
            ->when($month, fn($q) => $q->whereMonth('start_date', $month))
            ->orderByDesc('start_date')
            ->get();

        $totalCancelled = $events->where('status', 'Cancelled')->count();
        $totalEventsMonth = $events->whereIn('status', ['Upcoming', 'Ongoing', 'Completed'])->count();

        $completedEvents = $events->where('status', 'Completed');
        $completedEventsMonth = $completedEvents->count();

        $totalParticipants = 0;
        $totalAttended = 0;
        $attendedUserIds = collect();

        $eventsData = $completedEvents->values()->transform(function ($event) use (
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

        $avgAttendanceRate = $totalParticipants > 0
            ? round(($totalAttended / $totalParticipants) * 100, 1)
            : 0;

        $noShowGap = $totalParticipants - $totalAttended;
        $topEvent = $eventsData->sortByDesc('attended_count')->first();

        $loyalParticipantsCount = $attendedUserIds
            ->countBy()
            ->filter(fn($count) => $count > 1)
            ->count();

        $monthName = $month ? date('F', mktime(0, 0, 0, $month, 1)) : '';

        $reportTitle = $month 
            ? "Event Report - {$monthName} {$year}" 
            : "Annual Event Report {$year}";

        $pdf = App::make('dompdf.wrapper');
        
        $pdf->loadView('pdf.report-event-pdf', [
            'events' => $eventsData,
            'totalEventsMonth' => $totalEventsMonth,
            'completedEventsMonth' => $completedEventsMonth,
            'totalCancelled' => $totalCancelled,
            'avgAttendanceRate' => $avgAttendanceRate,
            'loyalParticipantsCount' => $loyalParticipantsCount,
            'noShowGap' => $noShowGap,
            'topEvent' => $topEvent,
            'reportTitle' => $reportTitle,
            'date' => now()->format('m/d/Y'),
        ]);

        return $pdf->stream("$reportTitle.pdf");
    }
}