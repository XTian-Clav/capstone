<x-filament-panels::page>
    <style>
        .text-blue { color: #013267; }
        .text-green { color: #15803d; }
        .text-yellow { color: #ca8a04; }
        .text-red { color: #991b1b; }
        .card-label { font-size: 11px; font-weight: bold; color: #666; text-transform: uppercase; letter-spacing: 0.025em; margin-bottom: 8px; }
        .card-value { font-size: 24px; font-weight: bold; line-height: 1; }
        .card-sub { color: #666; font-size: 11px; margin-top: 4px; }
        .stat-card { background: white; padding: 20px; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
    </style>

    <div style="overflow: auto;">
        
        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">
            Event Performance Dashboard ({{ now()->format('F  Y') }})
        </h2>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
            <div class="stat-card">
                <div class="card-label">Active Events ({{ now()->format('F') }})</div>
                <div class="text-blue card-value">{{ $totalEventsMonth }}</div>
                <div class="card-sub">Upcoming, ongoing, and completed events</div>
            </div>

            <div class="stat-card">
                <div class="card-label">Completed Events ({{ now()->format('F') }})</div>
                <div class="text-green card-value">{{ $completedEventsMonth }}</div>
                <div class="card-sub">Successfully finished this month</div>
            </div>

            <div class="stat-card">
                <div class="card-label">Cancelled Events ({{ now()->format('F') }})</div>
                <div class="text-red card-value">{{ $totalCancelled }}</div>
                <div class="card-sub">Events cancelled within the month</div>
            </div>

            <div class="stat-card">
                <div class="card-label">Overall Attendance Rate</div>
                <div class="text-blue card-value">{{ $avgAttendanceRate }}%</div>
                <div class="card-sub">Total attended vs total registered participants</div>
            </div>

            <div class="stat-card">
                <div class="card-label">Loyal Participants</div>
                <div class="text-green card-value">{{ $loyalParticipantsCount }}</div>
                <div class="card-sub">Users who attended more than one event</div>
            </div>

            <div class="stat-card">
                <div class="card-label">No-Show Count</div>
                <div class="text-red card-value">{{ $noShowGap }}</div>
                <div class="card-sub">Registered participants who did not attend</div>
            </div>
        </div>

        @if($topEvent)
            <div style="background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%); padding: 25px; border-radius: 15px; border: 1px solid #86efac; margin-bottom: 30px; position: relative; overflow: hidden;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                    <h3 class="text-green" style="font-weight: bold; font-size: 14px; text-transform: uppercase;">
                        Top Attended Event for the month of {{ now()->format('F') }}
                    </h3>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                    <div>
                        <div style="font-size: 22px; font-weight: bold; color: #1a1a1a;">{{ $topEvent->event }}</div>
                        <div style="color: #666; font-size: 13px; margin-top: 4px;">
                            Venue: <strong>{{ $topEvent->location }}</strong>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div class="text-green" style="font-size: 32px; font-weight: bold;">
                            {{ $topEvent->attended_count }}
                        </div>
                        <div style="color: #666; font-size: 11px; font-weight: bold;">
                            ATTENDEES
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <h3 style="font-size: 16px; font-weight: bold; margin-bottom: 15px;">
            Completed Events ({{ now()->format('F') }})
        </h3>

        <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1);">
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 15px; text-align: left; color: #374151; font-weight: 600; width: 25%;">Event & Location</th>
                        <th style="padding: 15px; text-align: left; color: #374151; font-weight: 600; width: 12%;">Start Date</th>
                        <th style="padding: 15px; text-align: left; color: #374151; font-weight: 600; width: 12%;">End Date</th>
                        <th style="padding: 15px; text-align: center; color: #374151; font-weight: 600; width: 12%;">Total Participants</th>
                        <th style="padding: 15px; text-align: center; color: #374151; font-weight: 600; width: 12%;">Going</th>
                        <th style="padding: 15px; text-align: center; color: #374151; font-weight: 600; width: 12%;">Not Going</th>
                        <th style="padding: 15px; text-align: center; color: #374151; font-weight: 600; width: 15%;">Attendance Rate</th>
                    </tr>
                </thead>
                <tbody style="color: #374151;">
                    @foreach($events as $event)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 15px;">
                                <div style="font-weight: bold; font-size: 13px;">{{ $event->event }}</div>
                                <div style="font-size: 11px; color: #71717a;">{{ $event->location }}</div>
                            </td>
                            <td style="padding: 15px; color: #71717a;">
                                {{ $event->start_date->format('M d, Y') }}
                            </td>
                            <td style="padding: 15px; color: #71717a;">
                                {{ $event->end_date->format('M d, Y') }}
                            </td>
                            <td style="padding: 15px; text-align: center; font-weight: bold;">
                                {{ $event->total_registrations }}
                            </td>
                            <td class="text-green" style="padding: 15px; text-align: center; font-weight: bold;">
                                {{ $event->attended_count }}
                            </td>
                            <td class="text-red" style="padding: 15px; text-align: center; font-weight: bold;">
                                {{ $event->declined_count }}
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 700; background: {{ $event->attendance_rate >= 70 ? '#f0fdf4' : ($event->attendance_rate >= 50 ? '#fefce8' : '#fef2f2') }}; color: {{ $event->attendance_rate >= 70 ? '#15803d' : ($event->attendance_rate >= 50 ? '#854d0e' : '#991b1b') }}; border: 1px solid currentColor;">
                                    {{ $event->attendance_rate }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
