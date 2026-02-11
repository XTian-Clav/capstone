<x-filament-panels::page>
    <style>
        .text-blue { color: #013267; }
        .text-red { color: #991b1b; }
        .text-green { color: #15803d; }

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
    </style>

    @php
        $currentMonth = request('month');
        $currentYear = request('year', now()->year);
    @endphp

    <div class="filter-container">
        <div style="display: flex; gap: 30px; flex-wrap: wrap; align-items: flex-end;">
            
            <div style="flex: 1; min-width: 300px;">
                <span class="filter-label" style="display: block; margin-bottom: 14px; font-weight: 700; color: #666; font-size: 12px; text-transform: uppercase;">Month</span>
                <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                    @foreach(range(1, 12) as $m)
                        <a href="{{ request()->fullUrlWithQuery(['month' => $m]) }}" 
                        class="filter-btn {{ $currentMonth == $m ? 'active-btn' : 'inactive-btn' }}"
                        style="text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 12px;">
                            {{ date('M', mktime(0, 0, 0, $m, 1)) }}
                        </a>
                    @endforeach

                    <a href="{{ request()->fullUrlWithQuery(['month' => null]) }}" 
                    class="filter-btn {{ is_null($currentMonth) ? 'active-btn' : 'inactive-btn' }}"
                    style="text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: bold;">
                        All
                    </a>
                </div>
            </div>

            <div style="width: 160px;">
                <span class="filter-label">Year</span>
                
                <x-filament::dropdown placement="bottom-start">
                    <x-slot name="trigger">
                        <button 
                            type="button"
                            style="width: 100%; height: 36px; display: flex; align-items: center; justify-content: space-between; padding: 0 12px; background: white; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; font-weight: 500; color: #374151;"
                        >
                            {{ $currentYear }}
                            <x-filament::icon
                                icon="heroicon-m-chevron-down"
                                style="width: 16px; height: 16px; color: #9ca3af;"
                            />
                        </button>
                    </x-slot>
            
                    <x-filament::dropdown.list>
                        @foreach($availableYears as $y)
                            <x-filament::dropdown.list.item 
                                tag="a" 
                                href="{{ request()->fullUrlWithQuery(['year' => $y]) }}"
                                :color="$currentYear == $y ? 'primary' : 'gray'"
                            >
                                {{ $y }}
                            </x-filament::dropdown.list.item>
                        @endforeach
                    </x-filament::dropdown.list>
                </x-filament::dropdown>
            </div>
        </div>
    </div>

    <div style="overflow: auto;">
        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">Room Performance Summary</h2>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 25px;">
            <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                <div style="font-size: 12px; font-weight: bold; color: #666; margin-bottom: 8px;">Total Revenue (Approved)</div>
                <div class="text-green" style="font-size: 20px; font-weight: bold;">₱{{ number_format($totalRevenue, 2) }}</div>
                <div style="color: #666; font-size: 12px;">Actual earnings</div>
            </div>

            <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                <div style="font-size: 12px; font-weight: bold; color: #666; margin-bottom: 8px;">Lost Opportunity</div>
                <div class="text-red" style="font-size: 20px; font-weight: bold;">₱{{ number_format($totalLostRevenue, 2) }}</div>
                <div style="color: #666; font-size: 12px;">From rejected requests</div>
            </div>

            <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                <div style="font-size: 12px; font-weight: bold; color: #666; margin-bottom: 8px;">Total Room Units</div>
                <div class="text-blue" style="font-size: 20px; font-weight: bold;">{{ $totalRooms }} Rooms</div>
                <div style="color: #666; font-size: 12px;">Active rooms in system</div>
            </div>
        </div>
    
        <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">Utilization & Revenue Analysis</h3>
        
        <div style="background: white; border-radius: 10px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 25px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 12px 15px; text-align: left; color: #374151;">Room Details</th>
                        <th style="padding: 12px 15px; text-align: left; color: #374151;">Capacity</th>
                        <th style="padding: 12px 15px; text-align: center; color: #374151;">Approved</th>
                        <th style="padding: 12px 15px; text-align: center; color: #374151;">Rejected</th>
                        <th style="padding: 12px 15px; text-align: left; color: #374151;">Revenue / Loss</th>
                        <th style="padding: 12px 15px; text-align: left; color: #374151;">Popularity</th>
                    </tr>
                </thead>
                <tbody style="color: #374151;">
                    @foreach($rooms as $room)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 12px 15px;">
                                <div style="font-weight: bold;">{{ $room->room_type }}</div>
                                <div style="color: #666; font-size: 11px;">{{ $room->location }}</div>
                            </td>
                            <td style="padding: 12px 15px;">{{ $room->capacity }}</td>
                            <td class="text-green" style="padding: 12px 15px; text-align: center; font-weight: bold;">{{ $room->approved_count }}</td>
                            <td class="text-red" style="padding: 12px 15px; text-align: center; font-weight: bold;">{{ $room->rejected_count }}</td>
                            <td style="padding: 12px 15px; font-weight: 600;">
                                <div class="text-green">Revenue: ₱{{ number_format($room->revenue, 2) }}</div>
                                <div class="text-red">Loss: ₱{{ number_format($room->lost_revenue, 2) }}</div>
                            </td>
                            <td style="padding: 12px 15px; font-weight: bold;">
                                @php
                                    $maxBookings = $rooms->max('total_requests') ?: 1;
                                    $popularity = ($room->total_requests / $maxBookings) * 100;
                                @endphp
                                {{ round($popularity) }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div style="padding: 15px; border-radius: 10px; background-color: #f0fdf4; border: 1px solid #86efac; font-size: 12px;">
                <h4 class="text-green" style="font-weight: bold; margin-bottom: 10px;">Most Popular Room</h4>
                @php $topRoom = $rooms->sortByDesc('approved_count')->first(); @endphp
                @if($topRoom)
                    The <strong>{{ $topRoom->room_type }}</strong> is your most successful asset with 
                    <strong>{{ $topRoom->approved_count }}</strong> confirmed bookings, generating 
                    <strong class="text-green">₱{{ number_format($topRoom->revenue, 2) }}</strong> revenue.
                @endif
            </div>

            <div style="padding: 15px; border-radius: 10px; background-color: #fef2f2; border: 1px solid #fca5a5; font-size: 12px;">
                <h4 class="text-red" style="font-weight: bold; margin-bottom: 10px;">Revenue Lost</h4>
                @php $mostRejected = $rooms->sortByDesc('lost_revenue')->first(); @endphp
                @if($mostRejected && $mostRejected->lost_revenue > 0)
                    The <strong>{{ $mostRejected->room_type }}</strong> has the highest lost opportunity. You missed out on 
                    <strong class="text-red">₱{{ number_format($mostRejected->lost_revenue, 2) }}</strong> due to 
                    <strong>{{ $mostRejected->rejected_count }}</strong> rejections.
                @else
                    No significant revenue loss from rejected bookings.
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>