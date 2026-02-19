<x-filament-panels::page>
    <style>
        .text-blue { color: #013267; }
        .text-red { color: #991b1b; }
        .text-green { color: #15803d; }
        .text-orange { color: #c2410c; }
        .text-yellow { color: #ca8a04; }
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
                            <button type="button" style="width: 100%; height: 36px; display: flex; align-items: center; justify-content: space-between; padding: 0 12px; background: white; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; font-weight: 500; color: #374151;">
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

                <a href="{{ request()->url() }}?month={{ now()->month }}&year={{ now()->year }}" 
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
        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">Reservation Status Summary</h2>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 25px;">
            <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                <div style="font-size: 12px; font-weight: bold; color: #666; margin-bottom: 8px;">Completed Reservations</div>
                <div class="text-blue" style="font-size: 20px; font-weight: bold;">{{ $totalCompleted }}</div>
                <div style="color: #666; font-size: 11px;">Successfully returned</div>
            </div>

            <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                <div style="font-size: 12px; font-weight: bold; color: #666; margin-bottom: 8px;">Approved Reservations</div>
                <div class="text-green" style="font-size: 20px; font-weight: bold;">{{ $totalApproved }}</div>
                <div style="color: #666; font-size: 11px;">Active reservations</div>
            </div>

            <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                <div style="font-size: 12px; font-weight: bold; color: #666; margin-bottom: 8px;">Pending Reservations</div>
                <div class="text-yellow" style="font-size: 20px; font-weight: bold;">{{ $totalPending }}</div>
                <div style="color: #666; font-size: 11px;">Awaiting action</div>
            </div>

            <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                <div style="font-size: 12px; font-weight: bold; color: #666; margin-bottom: 8px;">Rejected Reservations</div>
                <div class="text-red" style="font-size: 20px; font-weight: bold;">{{ $totalRejected }}</div>
                <div style="color: #666; font-size: 11px;">Cancelled requests</div>
            </div>
        </div>

        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">Supply Inventory Overview</h2>
        
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 25px;">
            <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                <div style="font-size: 12px; font-weight: bold; color: #666; margin-bottom: 8px;">Total Supplies</div>
                <div class="text-blue" style="font-size: 20px; font-weight: bold;">{{ $totalSupply }}</div>
                <div style="color: #666; font-size: 11px;">Total units</div>
            </div>

            <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                <div style="font-size: 12px; font-weight: bold; color: #666; margin-bottom: 8px;">Available Supplies</div>
                <div class="text-green" style="font-size: 20px; font-weight: bold;">{{ $totalAvailable }}</div>
                <div style="color: #666; font-size: 11px;">Ready for use</div>
            </div>

            <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                <div style="font-size: 12px; font-weight: bold; color: #666; margin-bottom: 8px;">Reserved Supplies</div>
                <div class="text-yellow" style="font-size: 20px; font-weight: bold;">{{ $totalReserved }}</div>
                <div style="color: #666; font-size: 11px;">Currently borrowed</div>
            </div>

            <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                <div style="font-size: 12px; font-weight: bold; color: #666; margin-bottom: 8px;">Unavailable Supplies</div>
                <div class="text-red" style="font-size: 20px; font-weight: bold;">{{ $totalUnavailable }}</div>
                <div style="color: #666; font-size: 11px;">Out of service</div>
            </div>
        </div>

        @if($mostBorrowed && $mostBorrowed->borrow_count > 0)
            <div style="padding: 15px; border-radius: 10px; background-color: #f0fdf4; border: 1px solid #86efac; font-size: 12px; margin-bottom: 25px;">
                <h4 class="text-green" style="font-weight: bold; margin-bottom: 5px;">Most Frequently Borrowed</h4>
                <div>
                    The <strong>{{ $mostBorrowed->item_name }}</strong> is your most requested item 
                    with a total of <strong class="text-green">{{ $mostBorrowed->borrow_count }}</strong> reservation request.
                </div>
            </div>
        @endif

        <h3 style="font-size: 16px; font-weight: bold; margin-bottom: 15px;">Supply Details</h3>
        <div style="background: white; border-radius: 10px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 25px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 12px 15px; text-align: left; color: #374151;">Supply Name</th>
                        <th style="padding: 12px 15px; text-align: left; color: #374151;">Borrow Count</th>
                        <th style="padding: 12px 15px; text-align: left; color: #374151;">Location</th>
                        <th style="padding: 12px 15px; text-align: center; color: #374151;">Total</th>
                        <th style="padding: 12px 15px; text-align: center; color: #374151;">Available</th>
                        <th style="padding: 12px 15px; text-align: center; color: #374151;">Reserved</th>
                        <th style="padding: 12px 15px; text-align: center; color: #374151;">Unavailable</th>
                    </tr>
                </thead>
                <tbody style="color: #374151;">
                    @foreach($supplies as $supply)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 15px; font-weight: bold;">{{ $supply->item_name }}</td>
                        <td style="padding: 12px 15px; font-weight: 600;">{{ $supply->borrow_count }}</td>
                        <td style="padding: 12px 15px;">{{ $supply->location }}</td>
                        <td style="padding: 12px 15px; text-align: center; font-weight: 600;">{{ $supply->quantity }}</td>
                        <td class="text-green" style="padding: 12px 15px; text-align: center; font-weight: 600;">{{ $supply->available }}</td>
                        <td class="text-yellow" style="padding: 12px 15px; text-align: center; font-weight: 600;">{{ $supply->current_reserved_qty ?? 0 }}</td>
                        <td class="text-red" style="padding: 12px 15px; text-align: center; font-weight: 600;">{{ $supply->total_unavailable_qty ?? 0 }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">Supply Stock Alerts</h3>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 25px;">
            <div style="background: #fef2f2; padding: 15px; border-radius: 10px; border: 1px solid #fca5a5;">
                <div style="font-size: 12px; font-weight: bold; color: #991b1b; margin-bottom: 8px;">Out of Stock</div>
                <div class="text-red" style="font-size: 20px; font-weight: bold;">{{ $outOfStock->count() }}</div>
                <div style="color: #666; font-size: 11px;">Immediate restock required</div>
            </div>
            <div style="background: #fff7ed; padding: 15px; border-radius: 10px; border: 1px solid #fdba74;">
                <div style="font-size: 12px; font-weight: bold; color: #9a3412; margin-bottom: 8px;">Critical Stock</div>
                <div class="text-orange" style="font-size: 20px; font-weight: bold;">{{ $criticalStock->count() }}</div>
                <div style="color: #666; font-size: 11px;">below 25% available</div>
            </div>
            <div style="background: #fefce8; padding: 15px; border-radius: 10px; border: 1px solid #facc15;">
                <div style="font-size: 12px; font-weight: bold; color: #854d0e; margin-bottom: 8px;">Low Stock</div>
                <div class="text-yellow" style="font-size: 20px; font-weight: bold;">{{ $lowStock->count() }}</div>
                <div style="color: #666; font-size: 11px;">26-50% available</div>
            </div>
        </div>

        @if($outOfStock->count() > 0)
            <div style="background: white; border-radius: 10px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 25px;">
                <div style="background: #fef2f2; padding: 10px 15px; border-bottom: 1px solid #fee2e2;">
                    <h3 class="text-red" style="font-size: 13px; font-weight: bold;">Out of Stock Items</h3>
                </div>
                <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 10px 15px; text-align: left; color: #374151;">Supply</th>
                            <th style="padding: 10px 15px; text-align: center; width: 15%;">Total</th>
                            <th style="padding: 10px 15px; text-align: center; width: 15%;">Reserved</th>
                            <th style="padding: 10px 15px; text-align: left; width: 25%;">Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($outOfStock as $supply)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 10px 15px; font-weight: bold;">{{ $supply->item_name }}</td>
                                <td style="padding: 10px 15px; text-align: center; font-weight: 600;">{{ $supply->quantity }}</td>
                                <td class="text-yellow" style="padding: 10px 15px; text-align: center;">{{ $supply->reserved }}</td>
                                <td style="padding: 10px 15px;">{{ $supply->location }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if($criticalStock->count() > 0)
            <div style="background: white; border-radius: 10px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 25px;">
                <div style="background: #fff7ed; padding: 10px 15px; border-bottom: 1px solid #ffedd5;">
                    <h3 class="text-orange" style="font-size: 13px; font-weight: bold;">Critical Stock (below 25%)</h3>
                </div>
                <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 10px 15px; text-align: left; color: #374151;">Supply</th>
                            <th style="padding: 10px 15px; text-align: center; width: 15%;">Remaining</th>
                            <th style="padding: 10px 15px; text-align: center; width: 15%;">Total</th>
                            <th style="padding: 10px 15px; text-align: center; width: 15%;">Availability %</th>
                            <th style="padding: 10px 15px; text-align: left; width: 25%;">Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($criticalStock as $supply)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 10px 15px; font-weight: bold;">{{ $supply->item_name }}</td>
                                <td class="text-orange" style="padding: 10px 15px; text-align: center; font-weight: 600;">{{ $supply->available }}</td>
                                <td style="padding: 10px 15px; text-align: center; font-weight: 600;">{{ $supply->quantity }}</td>
                                <td style="padding: 10px 15px; text-align: center;">{{ round($supply->availability_percentage, 1) }}%</td>
                                <td style="padding: 10px 15px;">{{ $supply->location }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if($lowStock->count() > 0)
            <div style="background: white; border-radius: 10px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 25px;">
                <div style="background: #fefce8; padding: 10px 15px; border-bottom: 1px solid #fef3c7;">
                    <h3 class="text-yellow" style="font-size: 13px; font-weight: bold;">Low Stock (26-50%)</h3>
                </div>
                <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 10px 15px; text-align: left; color: #374151;">Supply</th>
                            <th style="padding: 10px 15px; text-align: center; width: 15%;">Remaining</th>
                            <th style="padding: 10px 15px; text-align: center; width: 15%;">Total</th>
                            <th style="padding: 10px 15px; text-align: center; width: 15%;">Availability %</th>
                            <th style="padding: 10px 15px; text-align: left; width: 25%;">Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowStock as $supply)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 10px 15px; font-weight: bold;">{{ $supply->item_name }}</td>
                                <td class="text-yellow" style="padding: 10px 15px; text-align: center; font-weight: 600;">{{ $supply->available }}</td>
                                <td style="padding: 10px 15px; text-align: center; font-weight: 600;">{{ $supply->quantity }}</td>
                                <td style="padding: 10px 15px; text-align: center;">{{ round($supply->availability_percentage, 1) }}%</td>
                                <td style="padding: 10px 15px;">{{ $supply->location }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if($outOfStock->count() === 0 && $criticalStock->count() === 0 && $lowStock->count() === 0)
            <div style="padding: 20px; text-align: center; background-color: #f0fdf4; border: 1px solid #dcfce7; color: #15803d; border-radius: 10px; font-weight: bold; font-size: 12px;">
                All supply stock levels are healthy.
            </div>
        @endif
    </div>
</x-filament-panels::page>