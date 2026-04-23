<x-filament-panels::page>
    <style>
        .text-blue   { color: #013267; } .dark .text-blue   { color: #60a5fa; }
        .text-red    { color: #991b1b; } .dark .text-red    { color: #f87171; }
        .text-green  { color: #15803d; } .dark .text-green  { color: #4ade80; }
        .text-orange { color: #c2410c; } .dark .text-orange { color: #fb923c; }
        .text-yellow { color: #ca8a04; } .dark .text-yellow { color: #facc15; }

        .widget-card, .filter-container, .table-card {
            background: white; 
            padding: 20px; 
            border-radius: 10px; 
            border: 1px solid #e5e7eb; 
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }

        .table-card {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    
        .widget-label, .filter-label {
            font-size: 11px; font-weight: bold; color: #555;
            text-transform: uppercase; margin-bottom: 10px; display: block;
        }
    
        .filter-btn {
            padding: 6px 12px; font-size: 12px; border-radius: 6px;
            font-weight: 600; text-decoration: none; transition: all 0.2s; border: 1px solid #e5e7eb;
        }
        .active-btn { background-color: #fe800d; color: white; border-color: #fe800d; }
        .inactive-btn { background-color: #f9fafb; color: #27272a; }

        .alert-danger, .alert-warning, .alert-low, .alert-success, .featured-card {
            padding: 15px; border-radius: 10px; margin-bottom: 25px;
        }
        .featured-card { font-size: 12px; }

        .table-header-danger, .table-header-warning, .table-header-low {
            padding: 12px 15px;
            margin-bottom: 0;
            border-radius: 10px 10px 0 0;
            border-bottom: none;
            font-size: 12px;
            font-weight: bold;
        }

        .alert-danger,  .table-header-danger  { background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; }
        .alert-warning, .table-header-warning { background: #fff7ed; border: 1px solid #fdba74; color: #c2410c; }
        .alert-low,     .table-header-low     { background: #fefce8; border: 1px solid #facc15; color: #ca8a04; }
        .alert-success, .featured-card        { background: #f0fdf4; border: 1px solid #86efac; color: #15803d; }

        .dark .widget-card, .dark .filter-container, .dark .table-card { background: #18181b !important; border-color: #333 !important; color: #ffffff; }
        .dark .widget-label, .dark .filter-label, .dark h2, .dark h3 { color: #ffffff !important; }
    
        .dark .inactive-btn, .dark .year-dropdown-btn, .dark .reset-btn, .dark table thead tr {
            background-color: #27272a !important; color: #ffffff !important; border-color: #27272a !important;
        }
        
        .dark table tbody tr { color: #d1d5db !important; }
        .dark thead, .dark tr, .dark th, .dark td { border-color: #333 !important; }
        .dark thead th { color: white !important; background-color: #27272a !important; }
        
        .dark .alert-danger,  .dark .table-header-danger  { background: #3b1e1e !important; border-color: #991b1b !important; color: #fee2e2 !important; }
        .dark .alert-warning, .dark .table-header-warning { background: #3b2318 !important; border-color: #9a3412 !important; color: #ffedd5 !important; }
        .dark .alert-low,     .dark .table-header-low     { background: #3b3518 !important; border-color: #854d0e !important; color: #fef9c3 !important; }
        .dark .alert-success, .dark .featured-card        { background: #064e3b !important; border-color: #10b981 !important; color: #ecfdf5 !important; }
    </style>

    @php
        $currentMonth = request('month');
        $currentYear = request('year', now()->year);
        $monthLabel = $currentMonth 
            ? date('F', mktime(0, 0, 0, $currentMonth, 1)) 
            : 'Annual';
    @endphp

    <div class="filter-container">
        <div style="display: flex; gap: 30px; flex-wrap: wrap; align-items: flex-end;">
            
            <div style="flex: 1; min-width: 300px;">
                <span class="filter-label" style="margin-bottom: 14px;">Month</span>
                <div style="display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
                    @foreach(range(1, 12) as $m)
                        <a href="{{ request()->fullUrlWithQuery(['month' => $m]) }}" 
                        class="filter-btn {{ $currentMonth == $m ? 'active-btn' : 'inactive-btn' }}">
                            {{ date('M', mktime(0, 0, 0, $m, 1)) }}
                        </a>
                    @endforeach

                    <a href="{{ request()->fullUrlWithQuery(['month' => null]) }}" 
                    class="filter-btn {{ is_null($currentMonth) ? 'active-btn' : 'inactive-btn' }}">
                        All Months
                    </a>
                </div>
            </div>

            <div style="display: flex; gap: 10px; align-items: flex-end;">
                <div style="width: 140px;">
                    <span class="filter-label">Year</span>
                    <x-filament::dropdown placement="bottom-start">
                        <x-slot name="trigger">
                            <button type="button" class="year-dropdown-btn" style="width: 100%; height: 36px; display: flex; align-items: center; justify-content: space-between; padding: 0 12px; background: white; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; font-weight: 500; color: #374151;">
                                {{ $currentYear }}
                                <x-filament::icon icon="heroicon-m-chevron-down" style="width: 16px; height: 16px; color: #9ca3af;" />
                            </button>
                        </x-slot>
                        <x-filament::dropdown.list>
                            @foreach($availableYears as $y)
                                <x-filament::dropdown.list.item 
                                    tag="a" 
                                    href="{{ request()->fullUrlWithQuery(['year' => $y]) }}"
                                    :color="$currentYear == $y ? 'warning' : 'gray'">
                                    {{ $y }}
                                </x-filament::dropdown.list.item>
                            @endforeach
                        </x-filament::dropdown.list>
                    </x-filament::dropdown>
                </div>

                <a href="{{ request()->url() }}?month={{ now()->month }}&year={{ now()->year }}" class="reset-btn"
                style="height: 36px; padding: 0 12px; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 6px; display: flex; align-items: center; gap: 5px; color: #374151; font-size: 12px; font-weight: 600; text-decoration: none; transition: 0.2s;"
                onmouseover="this.style.background='#e5e7eb'" 
                onmouseout="this.style.background='#f3f4f6'">
                    <x-filament::icon icon="heroicon-m-arrow-path" style="width: 14px; height: 14px;" />
                    Reset
                </a>
            </div>
        </div>
    </div>

    <div style="overflow: auto;">
        
        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">
            Event Performance Dashboard ({{ $monthLabel }} {{ $currentYear }})
        </h2>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
            <div class="widget-card">
                <div class="widget-label">Active Events</div>
                <div class="text-blue" style="font-size: 20px; font-weight: bold;">{{ $totalEventsMonth }}</div>
                <div style="color: #555; font-size: 11px;">Upcoming, ongoing, and completed events</div>
            </div>

            <div class="widget-card">
                <div class="widget-label">Completed Events</div>
                <div class="text-green" style="font-size: 20px; font-weight: bold;">{{ $completedEventsMonth }}</div>
                <div style="color: #555; font-size: 11px;">Successfully finished this month</div>
            </div>

            <div class="widget-card">
                <div class="widget-label">Cancelled Events</div>
                <div class="text-red" style="font-size: 20px; font-weight: bold;">{{ $totalCancelled }}</div>
                <div style="color: #555; font-size: 11px;">Events cancelled within the month</div>
            </div>

            <div class="widget-card">
                <div class="widget-label">Overall Attendance Rate</div>
                <div class="text-blue" style="font-size: 20px; font-weight: bold;">{{ $avgAttendanceRate }}%</div>
                <div style="color: #555; font-size: 11px;">Total attended vs total registered participants</div>
            </div>

            <div class="widget-card">
                <div class="widget-label">Loyal Participants</div>
                <div class="text-green" style="font-size: 20px; font-weight: bold;">{{ $loyalParticipantsCount }}</div>
                <div style="color: #555; font-size: 11px;">Users who attended more than one event</div>
            </div>

            <div class="widget-card">
                <div class="widget-label">No-Show Count</div>
                <div class="text-red" style="font-size: 20px; font-weight: bold;">{{ $noShowGap }}</div>
                <div style="color: #555; font-size: 11px;">Registered participants who did not attend</div>
            </div>
        </div>

        @if($topEvent)
            <div class="featured-card">
                <div style="display: grid; grid-template-columns: 1fr auto; gap: 10px;">
                    <div style="grid-column: span 2; margin-bottom: 12px;">
                        <h3 class="text-green" style="font-weight: bold; font-size: 14px; text-transform: uppercase; margin: 0;">
                            Top Attended Event: {{ $monthLabel }} {{ $currentYear }}
                        </h3>
                    </div>

                    <div>
                        <div class="text-green" style="font-size: 22px; font-weight: bold;">{{ $topEvent->event }}</div>
                        <div class="widget-label">
                            Venue: <strong>{{ $topEvent->location }}</strong>
                        </div>
                    </div>

                    <div style="text-align: right; align-self: end;">
                        <div class="text-green" style="font-size: 32px; font-weight: bold; line-height: 1;">
                            {{ $topEvent->attended_count }}
                        </div>
                        <div class="widget-label">
                            ATTENDEES
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <h3 style="font-size: 16px; font-weight: bold; margin-bottom: 15px;">
            Completed Events - {{ $monthLabel }} {{ $currentYear }}
        </h3>

        <div style="border-radius: 10px; overflow: hidden;">
            <table class="table-card" style="width: 100%; border-collapse: collapse; font-size: 12px;">
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
                    @forelse($events as $event)
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
                                <span class="{{ $event->attendance_rate >= 70 ? 'alert-success text-green' : ($event->attendance_rate >= 50 ? 'alert-low text-yellow' : 'alert-danger text-red') }}" 
                                      style="padding: 4px 10px; font-size: 12px; font-weight: 700;">
                                    {{ $event->attendance_rate }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 40px; text-align: center; color: #71717a;">
                                No events found for this period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
