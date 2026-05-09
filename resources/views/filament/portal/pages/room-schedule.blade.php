<x-filament-panels::page>
    <style>
        .filter-container { background: white; padding: 20px; border-radius: 10px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); margin-bottom: 25px; }
        .filter-label { font-size: 11px; font-weight: bold; color: #555; text-transform: uppercase; margin-bottom: 10px; display: block; }
        .filter-btn { padding: 6px 12px; font-size: 12px; border-radius: 6px; font-weight: 600; text-decoration: none; transition: all 0.2s; border: 1px solid #e5e7eb; }
        .active-btn { background-color: #fe800d; color: white; border-color: #fe800d; }
        .inactive-btn { background-color: #f9fafb; color: #374151; }
        .inactive-btn:hover { background-color: #f3f4f6; }
        
        .calendar-grid { display: grid; width: 100%; background: #e5e7eb; gap: 1px; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; grid-template-columns: repeat(1, 1fr); }
        .calendar-cell { background: white; min-height: 120px; padding: 8px; display: flex; flex-direction: column; min-width: 0; }
        .day-off { background-color: #f9fafb; color: #d1d5db; }
        .today-mark { background: #fe800d; color: white; padding: 2px 6px; border-radius: 4px; font-size: 12px; }
        
        .res-badge { font-size: 10px; background: #f0f7ff; height: 80px; width: clamp(125px, 100%, 180px); border-left: 3px solid #013267; padding: 4px; margin-top: 4px; border-radius: 2px; color: #013267; overflow: hidden; text-overflow: ellipsis; transition: all 0.2s ease; cursor: pointer; position: relative; z-index: 1; }
        .res-badge strong { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.1; white-space: normal;}
        .day-badge { background: #60a5fa; color: #f0f7ff; padding: 1px 4px; border-radius: 3px; font-size: 8px; font-weight: 900; text-transform: uppercase; float: right; }

        .reservation-container { transition: max-height 0.5s ease-in-out; overflow: hidden; position: relative; display: block;}
        .is-expanded { max-height: 1200px !important; }
        .is-collapsed { max-height: 168px !important; }

        .expand-trigger { font-size: 9px; font-weight: 700; padding: 2px 6px; border-radius: 4px; border: none; cursor: pointer; transition: all 0.2s ease; background: rgba(1, 50, 103, 0.05); color: #013267; }
        .expand-trigger:hover { background: rgba(1, 50, 103, 0.1); }
        
        .dark .filter-container { background: #18181b !important; border-color: #333 !important; }
        .dark .filter-label, .dark h2 { color: #ffffff !important; }
        
        .dark .inactive-btn { background-color: #27272a !important; color: #ffffff !important; border-color: #333 !important; }
        .dark .inactive-btn:hover { background-color: #3f3f46 !important; }
        
        .dark .calendar-grid { background: #333 !important; border-color: #333 !important; }
        .dark .calendar-cell { background: #18181b !important; color: #ffffff !important; }
        .dark .day-off { background-color: #202023 !important; color: #4b5563 !important; }
        
        .dark .reset-btn { background: #27272a !important; border-color: #333 !important; color: #ffffff !important; }
        .dark .reset-btn:hover { background: #3f3f46 !important; }

        .dark .res-badge { background: #1e293b !important; color: #60a5fa !important; border-left-color: #60a5fa !important; }
        .dark .day-badge { background: #60a5fa; color: #1e293b; }

        .dark .expand-trigger { background: rgba(96, 165, 250, 0.1) !important; color: #60a5fa !important; }
        .dark .expand-trigger:hover { background: rgba(96, 165, 250, 0.2) !important; }

        @media (min-width: 1024px) {
            .calendar-grid { grid-template-columns: repeat(7, 1fr); table-layout: fixed; }
            .calendar-cell { padding: 4px; min-width: 0; overflow: hidden; }
            .res-badge { width: clamp(110px, 100%, 180px); margin-left: 0; margin-right: 0; }
        }

        @media (max-width: 640px) {
            .res-badge { width: 100%; }
        }
    </style>

    <div class="filter-container">
        <div style="display: flex; gap: 30px; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 300px;">
                <span class="filter-label" style="margin-bottom: 14px;">Month</span>
                <div style="display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
                    @foreach(range(1, 12) as $m)
                        <button 
                            type="button"
                            onclick="window.location.href = '{{ request()->fullUrlWithQuery(['month' => $m]) }}'"
                            class="filter-btn {{ $currentMonth == $m ? 'active-btn' : 'inactive-btn' }}"
                            style="cursor: pointer;">
                            {{ date('M', mktime(0, 0, 0, $m, 1)) }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div style="display: flex; align-items: flex-end;">
                <button 
                    type="button"
                    onclick="window.location.href = '{{ request()->url() }}?month={{ now()->month }}'"
                    class="reset-btn"
                    style="height: 36px; padding: 0 12px; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 6px; display: flex; align-items: center; gap: 5px; color: #374151; font-size: 12px; font-weight: 600; cursor: pointer; transition: 0.2s;"
                    onmouseover="this.style.background='#e5e7eb'" 
                    onmouseout="this.style.background='#f3f4f6'">
                    <x-filament::icon icon="heroicon-m-arrow-path" style="width: 14px; height: 14px;" />
                    Reset
                </button>
            </div>
        </div>
    </div>

    <div style="margin-bottom: -20px;">
        <h2 style="font-size: 18px; font-weight: 900; color: #111827; text-transform: uppercase; margin: 0; padding: 0; line-height: 1;">
            {{ date('F', mktime(0, 0, 0, $currentMonth, 1)) }} {{ $currentYear }}
        </h2>
    </div>

    <div class="calendar-grid">
        @foreach(collect($calendarGrid)->chunk(7) as $week)
            <div x-data="{ weekExpanded: false }" class="week-row-container" style="display: contents;">
                @foreach($week as $day)
                    <div @class(['calendar-cell', 'day-off' => !$day['isCurrentMonth']]) 
                         style="min-height: 90px;">
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                            <span class="{{ $day['isToday'] ? 'today-mark' : 'font-bold' }}">
                                {{ $day['date']->day }}
                            </span>

                            @if($day['isCurrentMonth'] && isset($reservationsByDay[$day['date']->day]) && count($reservationsByDay[$day['date']->day]) > 2)
                                <button @click="weekExpanded = !weekExpanded" 
                                        type="button" 
                                        class="expand-trigger">
                                    <span x-text="weekExpanded ? 'HIDE' : '+{{ count($reservationsByDay[$day['date']->day]) - 2 }} MORE'"></span>
                                </button>
                            @endif
                        </div>
    
                        @if($day['isCurrentMonth'] && isset($reservationsByDay[$day['date']->day]))
                            @php 
                                $dayReservations = $reservationsByDay[$day['date']->day];
                                $maxLane = max(array_keys($dayReservations));
                            @endphp

                            <div class="reservation-container" :class="weekExpanded ? 'is-expanded' : 'is-collapsed'">
                                @for($i = 0; $i <= $maxLane; $i++)
                                    @if(isset($dayReservations[$i]))
                                        @php $res = $dayReservations[$i]; @endphp
                                        <div class="res-badge">
                                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
                                                <strong style="flex: 1; display: block; word-break: break-word; line-height: 1.2;">
                                                    {{ $res['room_type'] }}
                                                </strong>
                                                
                                                @if(isset($res['day_label']))
                                                    <span class="day-badge">{{ $res['day_label'] }}</span>
                                                @endif
                                            </div>
    
                                            <span style="font-size: 10px;">{{ $res['time'] }}</span>
                                            
                                            <div style="font-size: 10px;">
                                                <div style="opacity: 0.8; margin-top: 5px;">Reserved by:</div>
                                                <div style="font-weight: 600; word-break: break-word; text-transform: uppercase; line-height: 1.2">{{ $res['user_name'] }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <div style="height: 80px; margin-bottom: 4px; visibility: hidden;" aria-hidden="true"></div>
                                    @endif
                                @endfor
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</x-filament-panels::page>