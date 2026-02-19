<x-filament-panels::page>
    <style>
        .filter-container {
            background: white; 
            padding: 20px; 
            border-radius: 10px; 
            border: 1px solid #e5e7eb; 
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }
        .filter-label {
            font-size: 11px;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 10px;
            display: block;
        }
        .filter-btn {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid #e5e7eb;
        }
        .active-btn {
            background-color: #fe800d;
            color: white;
            border-color: #fe800d;
        }
        .inactive-btn {
            background-color: #f9fafb;
            color: #374151;
        }
        .inactive-btn:hover {
            background-color: #f3f4f6;
        }

        .calendar-grid {
            display: grid;
            width: 100%;
            background: #e5e7eb;
            gap: 1px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            grid-template-columns: repeat(1, 1fr);
        }

        @media (min-width: 1024px) {
            .calendar-grid { grid-template-columns: repeat(7, 1fr); }
        }

        .calendar-cell {
            background: white;
            min-height: 120px;
            padding: 10px;
            display: flex;
            flex-direction: column;
        }

        .day-off { background-color: #f9fafb; color: #d1d5db; }
        
        .today-mark {
            background: #fe800d; color: white; padding: 2px 6px;
            border-radius: 4px; font-size: 12px;
        }

        .res-badge {
            font-size: 10px; 
            background: #f0f7ff; 
            border-left: 3px solid #013267; 
            padding: 4px; 
            margin-top: 4px; 
            border-radius: 2px; 
            color: #013267;
            white-space: nowrap; 
            overflow: hidden; 
            text-overflow: ellipsis;
            transition: all 0.2s ease;
            cursor: pointer;
            position: relative;
            z-index: 1;
        }

        .res-badge:hover {
            background-color: #ffffff !important;
            border-left-color: #fe800d !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
            white-space: normal !important; 
            z-index: 50 !important;
            height: auto !important; /* Allows badge to grow to show the name */
        }

        /* This is the magic part: */
        .res-badge:hover .user-details {
            display: block !important;
        }
    </style>

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
                </div>
            </div>

            <div style="display: flex; align-items: flex-end;">
                <a href="{{ request()->url() }}?month={{ now()->month }}" 
                   style="height: 36px; padding: 0 12px; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 6px; display: flex; align-items: center; gap: 5px; color: #374151; font-size: 12px; font-weight: 600; text-decoration: none; transition: 0.2s;"
                   onmouseover="this.style.background='#e5e7eb'" 
                   onmouseout="this.style.background='#f3f4f6'">
                    <x-filament::icon icon="heroicon-m-arrow-path" style="width: 14px; height: 14px;" />
                    Reset
                </a>
            </div>
        </div>
    </div>

    <div style="margin-bottom: -20px;">
        <h2 style="font-size: 18px; font-weight: 900; color: #111827; text-transform: uppercase; margin: 0; padding: 0; line-height: 1;">
            {{ date('F', mktime(0, 0, 0, $currentMonth, 1)) }} {{ $currentYear }}
        </h2>
    </div>

    <div class="calendar-grid">
        @foreach($calendarGrid as $day)
            {{-- Reduced min-height to 90px to fix the 'too much spacing' issue --}}
            <div @class(['calendar-cell', 'day-off' => !$day['isCurrentMonth']]) style="min-height: 90px;">
                <div style="margin-bottom: 5px;">
                    <span class="{{ $day['isToday'] ? 'today-mark' : 'font-bold' }}">
                        {{ $day['date']->day }}
                    </span>
                </div>

                @if($day['isCurrentMonth'] && isset($reservationsByDay[$day['date']->day]))
                    @foreach($reservationsByDay[$day['date']->day] as $res)
                        <div class="res-badge">
                            <strong style="display: block;">{{ $res['room_type'] }}</strong>
                            <span style="font-size: 10px;">{{ $res['time'] }}</span>
                            
                            <div class="user-details" style="display: none; font-size: 10px; margin-top: 2px; text-transform: uppercase;">
                                Reserved by: {{ $res['user_name'] }}
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        @endforeach
    </div>
</x-filament-panels::page>