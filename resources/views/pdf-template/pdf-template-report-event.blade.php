<style>
    .text-blue { color: #013267; }
    .text-red { color: #991b1b; }
    .text-green { color: #15803d; }
</style>

@php
    $currentMonth = request('month');
    $currentYear = request('year', now()->year);
    $monthLabel = $currentMonth 
        ? date('F', mktime(0, 0, 0, $currentMonth, 1)) 
        : 'Annual';
@endphp

<div style="font-family: sans-serif; overflow: auto;">
    
    <h2 style="font-size: 12px; font-weight: bold; text-transform: uppercase; margin-bottom: 15px; color: #333; background-color: #fff7ed; padding: 8px 12px; border-left: 4px solid #fe800d;">
        Event Performance Analysis ({{ $monthLabel }} {{ $currentYear }})
    </h2>
    
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 12px;">
        <tr>
            <th style="border-bottom: 1px solid #eee; padding: 10px; text-align: left; color: #666; width: 33%;">Active Events</th>
            <th style="border-bottom: 1px solid #eee; padding: 10px; text-align: left; color: #666; width: 33%;">Completed Events</th>
            <th style="border-bottom: 1px solid #eee; padding: 10px; text-align: left; color: #666; width: 34%;">Cancelled Events</th>
        </tr>
        <tr>
            <td style="padding: 15px 10px;">
                <div class="text-blue" style="font-size: 16px; font-weight: bold;">{{ $totalEventsMonth }}</div>
                <div style="color: #999; font-size: 11px; text-transform: uppercase;">Upcoming, ongoing, & completed</div>
            </td>
            <td style="padding: 15px 10px;">
                <div class="text-green" style="font-size: 16px; font-weight: bold;">{{ $completedEventsMonth }}</div>
                <div style="color: #999; font-size: 11px; text-transform: uppercase;">Successfully finished</div>
            </td>
            <td style="padding: 15px 10px;">
                <div class="text-red" style="font-size: 16px; font-weight: bold;">{{ $totalCancelled }}</div>
                <div style="color: #999; font-size: 11px; text-transform: uppercase;">Cancelled this month</div>
            </td>
        </tr>
        <tr>
            <th style="border-bottom: 1px solid #eee; padding: 10px; text-align: left; color: #666;">Overall Attendance Rate</th>
            <th style="border-bottom: 1px solid #eee; padding: 10px; text-align: left; color: #666;">Loyal Participants</th>
            <th style="border-bottom: 1px solid #eee; padding: 10px; text-align: left; color: #666;">No-Show Count</th>
        </tr>
        <tr>
            <td style="padding: 15px 10px;">
                <div class="text-blue" style="font-size: 16px; font-weight: bold;">{{ $avgAttendanceRate }}%</div>
                <div style="color: #999; font-size: 11px; text-transform: uppercase;">Attended vs Registered</div>
            </td>
            <td style="padding: 15px 10px;">
                <div class="text-green" style="font-size: 16px; font-weight: bold;">{{ $loyalParticipantsCount }}</div>
                <div style="color: #999; font-size: 11px; text-transform: uppercase;">More than one event</div>
            </td>
            <td style="padding: 15px 10px;">
                <div class="text-red" style="font-size: 16px; font-weight: bold;">{{ $noShowGap }}</div>
                <div style="color: #999; font-size: 11px; text-transform: uppercase;">Did not attend</div>
            </td>
        </tr>
    </table>

    @if($topEvent)
        <div style="border: 1px solid #eee; margin-bottom: 25px; font-size: 12px; border-radius: 4px;">
            <div class="text-green" style="background-color: #f0fdf4; padding: 8px 12px; font-weight: bold; border-bottom: 1px solid #eee; text-transform: uppercase;">
                Top Attended Event: {{ $monthLabel }} {{ $currentYear }}
            </div>
            <div style="padding: 12px; color: #444; line-height: 1.4;">
                The <strong>{{ $topEvent->event }}</strong> at <strong>{{ $topEvent->location }}</strong> is the most successful event with 
                <strong class="text-green">{{ $topEvent->attended_count }} confirmed attendees</strong>.
            </div>
        </div>
    @endif

    <h2 style="font-size: 12px; font-weight: bold; text-transform: uppercase; margin-bottom: 15px; color: #333; background-color: #fff7ed; padding: 8px 12px; border-left: 4px solid #fe800d;">
        Completed Events - {{ $monthLabel }} {{ $currentYear }}
    </h2>

    <table style="width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 25px;">
        <thead>
            <tr style="background-color: #fcfcfc;">
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: left; color: #555;">Event & Location</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: left; color: #555;">Dates</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: center; color: #555;">Total</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: center; color: #555;">Going</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: center; color: #555;">Not Going</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: center; color: #555;">Rate</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
                <tr>
                    <td style="border-bottom: 1px solid #f5f5f5; padding: 10px;">
                        <div style="font-weight: bold; color: #333;">{{ $event->event }}</div>
                        <div style="color: #888; font-size: 11px;">{{ $event->location }}</div>
                    </td>
                    <td style="border-bottom: 1px solid #f5f5f5; padding: 10px; color: #666;">
                        {{ $event->start_date->format('M d') }} - {{ $event->end_date->format('M d, Y') }}
                    </td>
                    <td style="border-bottom: 1px solid #f5f5f5; padding: 10px; text-align: center; font-weight: bold;">
                        {{ $event->total_registrations }}
                    </td>
                    <td class="text-green" style="border-bottom: 1px solid #f5f5f5; padding: 10px; text-align: center; font-weight: bold;">
                        {{ $event->attended_count }}
                    </td>
                    <td class="text-red" style="border-bottom: 1px solid #f5f5f5; padding: 10px; text-align: center; font-weight: bold;">
                        {{ $event->declined_count }}
                    </td>
                    <td style="border-bottom: 1px solid #f5f5f5; padding: 10px; text-align: center; font-weight: bold;">
                        <span style="color: {{ $event->attendance_rate >= 70 ? '#15803d' : ($event->attendance_rate >= 50 ? '#ca8a04' : '#991b1b') }};">
                            {{ $event->attendance_rate }}%
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>