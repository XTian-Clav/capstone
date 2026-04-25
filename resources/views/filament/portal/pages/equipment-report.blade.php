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
    @endphp

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
            
                    <button 
                        type="button"
                        onclick="window.location.href = '{{ request()->fullUrlWithQuery(['month' => null]) }}'"
                        class="filter-btn {{ is_null($currentMonth) ? 'active-btn' : 'inactive-btn' }}"
                        style="cursor: pointer;">
                        All Months
                    </button>
                </div>
            </div>

            <div style="display: flex; gap: 10px; align-items: flex-end;">
                <div style="width: 140px;">
                    <span class="filter-label">Year</span>
                    <x-filament::dropdown placement="bottom-start">
                        <x-slot name="trigger">
                            <button type="button" class="year-dropdown-btn" style="width: 100%; height: 36px; display: flex; align-items: center; justify-content: space-between; padding: 0 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; font-weight: 500; color: #27272a;">
                                {{ $currentYear }}
                                <x-filament::icon icon="heroicon-m-chevron-down" style="width: 16px; height: 16px; color: #9ca3af;" />
                            </button>
                        </x-slot>
                        <x-filament::dropdown.list>
                            @foreach($availableYears as $y)
                                <x-filament::dropdown.list.item 
                                    tag="button"
                                    onclick="window.location.href = '{{ request()->fullUrlWithQuery(['year' => $y]) }}'"
                                    :color="$currentYear == $y ? 'warning' : 'gray'">
                                    {{ $y }}
                                </x-filament::dropdown.list.item>
                            @endforeach
                        </x-filament::dropdown.list>
                    </x-filament::dropdown>
                </div>

                <button 
                    type="button"
                    onclick="window.location.href = '{{ request()->url() }}?month={{ now()->month }}&year={{ now()->year }}'"
                    class="reset-btn"
                    style="height: 36px; padding: 0 12px; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 6px; display: flex; align-items: center; gap: 5px; color: #27272a; font-size: 12px; font-weight: 600; cursor: pointer; transition: 0.2s;"
                    onmouseover="this.style.background='#e5e7eb'" 
                    onmouseout="this.style.background='#f3f4f6'">
                    <x-filament::icon icon="heroicon-m-arrow-path" style="width: 14px; height: 14px;" />
                    Reset
                </button>
            </div>
        </div>
    </div>

    <div style="overflow: auto;">
        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">Reservation Status Summary</h2>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 25px;">
            <div class="widget-card">
                <div class="widget-label">Completed Reservations</div>
                <div class="text-blue" style="font-size: 20px; font-weight: bold;">{{ $totalCompleted }}</div>
                <div style="color: #555; font-size: 11px;">Successfully returned</div>
            </div>

            <div class="widget-card">
                <div class="widget-label">Approved Reservations</div>
                <div class="text-green" style="font-size: 20px; font-weight: bold;">{{ $totalApproved }}</div>
                <div style="color: #555; font-size: 11px;">Active reservations</div>
            </div>

            <div class="widget-card">
                <div class="widget-label">Pending Reservations</div>
                <div class="text-yellow" style="font-size: 20px; font-weight: bold;">{{ $totalPending }}</div>
                <div style="color: #555; font-size: 11px;">Awaiting action</div>
            </div>

            <div class="widget-card">
                <div class="widget-label">Rejected Reservations</div>
                <div class="text-red" style="font-size: 20px; font-weight: bold;">{{ $totalRejected }}</div>
                <div style="color: #555; font-size: 11px;">Cancelled requests</div>
            </div>
        </div>
        
        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">Equipment Inventory Overview</h2>
        
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 25px;">
            <div class="widget-card">
                <div class="widget-label">Total Equipment</div>
                <div class="text-blue" style="font-size: 20px; font-weight: bold;">{{ $totalEquipment }}</div>
                <div style="color: #555; font-size: 11px;">Total units</div>
            </div>

            <div class="widget-card">
                <div class="widget-label">Available Equipment</div>
                <div class="text-green" style="font-size: 20px; font-weight: bold;">{{ $totalAvailable }}</div>
                <div style="color: #555; font-size: 11px;">Ready for use</div>
            </div>

            <div class="widget-card">
                <div class="widget-label">Reserved Equipment</div>
                <div class="text-yellow" style="font-size: 20px; font-weight: bold;">{{ $totalReserved }}</div>
                <div style="color: #555; font-size: 11px;">Currently borrowed</div>
            </div>

            <div class="widget-card">
                <div class="widget-label">Unavailable Equipment</div>
                <div class="text-red" style="font-size: 20px; font-weight: bold;">{{ $totalUnavailable }}</div>
                <div style="color: #555; font-size: 11px;">Out of service</div>
            </div>
        </div>

        @if($mostBorrowed && $mostBorrowed->borrow_count > 0)
            <div class="featured-card">
                <h4 class="text-green" style="font-weight: bold; margin-bottom: 5px;">Most Frequently Borrowed</h4>
                <div>
                    The <strong>{{ $mostBorrowed->equipment_name }}</strong> is your most requested equipment 
                    with a total of <strong class="text-green">{{ $mostBorrowed->borrow_count }}</strong> approved reservations.
                </div>
            </div>
        @endif

        <h3 style="font-size: 16px; font-weight: bold; margin-bottom: 15px;">Equipment Details</h3>
        <div style="border-radius: 10px; overflow: hidden; margin-bottom: 25px;">
            <table class="table-card" style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 12px 15px; text-align: left; color: #27272a;">Equipment</th>
                        <th style="padding: 12px 15px; text-align: left; color: #27272a;">Borrow Count</th>
                        <th style="padding: 12px 15px; text-align: left; color: #27272a;">Location</th>
                        <th style="padding: 12px 15px; text-align: center; color: #27272a;">Total</th>
                        <th style="padding: 12px 15px; text-align: center; color: #27272a;">Available</th>
                        <th style="padding: 12px 15px; text-align: center; color: #27272a;">Reserved</th>
                        <th style="padding: 12px 15px; text-align: center; color: #27272a;">Unavailable</th>
                    </tr>
                </thead>
                <tbody style="color: #27272a;">
                    @foreach($equipments as $equipment)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 15px; font-weight: bold;">{{ $equipment->equipment_name }}</td>
                        <td style="padding: 12px 15px; font-weight: 600;">{{ $equipment->borrow_count }}</td>
                        <td style="padding: 12px 15px;">{{ $equipment->location }}</td>
                        <td style="padding: 12px 15px; text-align: center; font-weight: 600;">{{ $equipment->quantity }}</td>
                        <td class="text-green" style="padding: 12px 15px; text-align: center; font-weight: 600;">{{ $equipment->available }}</td>
                        <td class="text-yellow" style="padding: 12px 15px; text-align: center; font-weight: 600;">{{ $equipment->current_reserved_qty ?? 0 }}</td>
                        <td class="text-red" style="padding: 12px 15px; text-align: center; font-weight: 600;">{{ $equipment->total_unavailable_qty ?? 0 }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">Equipment Stock Alerts</h3>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 25px;">
            <div class="alert-danger">
                <div class="text-red" style="font-size: 12px; font-weight: bold; margin-bottom: 8px;">Out of Stock</div>
                <div class="text-red" style="font-size: 20px; font-weight: bold;">{{ $outOfStock->count() }}</div>
                <div class="text-red" style="font-size: 11px;">Immediate attention required</div>
            </div>
            <div class="alert-warning">
                <div class="text-orange" style="font-size: 12px; font-weight: bold; margin-bottom: 8px;">Critical Stock</div>
                <div class="text-orange" style="font-size: 20px; font-weight: bold;">{{ $criticalStock->count() }}</div>
                <div class="text-orange" style="font-size: 11px;">below 25% available</div>
            </div>
            <div class="alert-low">
                <div class="text-yellow" style="font-size: 12px; font-weight: bold; margin-bottom: 8px;">Low Stock</div>
                <div class="text-yellow" style="font-size: 20px; font-weight: bold;">{{ $lowStock->count() }}</div>
                <div class="text-yellow" style="font-size: 11px;">26-50% available</div>
            </div>
        </div>

        @if($outOfStock->count() > 0)
            <div style="border-radius: 10px; overflow: hidden; margin-bottom: 25px;">
                <div class="table-header-danger">
                    <h3 class="text-red" style="font-size: 13px; font-weight: bold;">Out of Stock Equipment</h3>
                </div>
                <table class="table-card" style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 10px 15px; text-align: left; color: #27272a;">Equipment</th>
                            <th style="padding: 10px 15px; text-align: center; width: 15%;">Total</th>
                            <th style="padding: 10px 15px; text-align: center; width: 15%;">Reserved</th>
                            <th style="padding: 10px 15px; text-align: left; width: 25%;">Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($outOfStock as $equipment)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 10px 15px; font-weight: bold;">{{ $equipment->equipment_name }}</td>
                                <td style="padding: 10px 15px; text-align: center; font-weight: 600;">{{ $equipment->quantity }}</td>
                                <td class="text-yellow" style="padding: 10px 15px; text-align: center;">{{ $equipment->reservations->where('status', 'Approved')->sum('quantity') }}</td>
                                <td style="padding: 10px 15px;">{{ $equipment->location }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if($criticalStock->count() > 0)
            <div style="border-radius: 10px; overflow: hidden; margin-bottom: 25px;">
                <div class="table-header-warning">
                    <h3 class="text-orange" style="font-size: 13px; font-weight: bold;">Critical Stock (below 25%)</h3>
                </div>
                <table class="table-card" style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 10px 15px; text-align: left; color: #27272a;">Equipment</th>
                            <th style="padding: 10px 15px; text-align: center; width: 15%;">Remaining</th>
                            <th style="padding: 10px 15px; text-align: center; width: 15%;">Total</th>
                            <th style="padding: 10px 15px; text-align: center; width: 15%;">Availability %</th>
                            <th style="padding: 10px 15px; text-align: left; width: 25%;">Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($criticalStock as $equipment)
                            @php
                                $res = $equipment->reservations->where('status', 'Approved')->sum('quantity');
                                $unav = $equipment->unavailable->sum('unavailable_quantity');
                                $avail = $equipment->quantity - $res - $unav;
                                $perc = ($equipment->quantity > 0) ? ($avail / $equipment->quantity) * 100 : 0;
                            @endphp
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 10px 15px; font-weight: bold;">{{ $equipment->equipment_name }}</td>
                                <td class="text-orange" style="padding: 10px 15px; text-align: center; font-weight: 600;">{{ $avail }}</td>
                                <td style="padding: 10px 15px; text-align: center; font-weight: 600;">{{ $equipment->quantity }}</td>
                                <td style="padding: 10px 15px; text-align: center;">{{ round($perc, 1) }}%</td>
                                <td style="padding: 10px 15px;">{{ $equipment->location }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if($lowStock->count() > 0)
            <div style="border-radius: 10px; overflow: hidden; margin-bottom: 25px;">
                <div class="table-header-low">
                    <h3 class="text-yellow" style="font-size: 13px; font-weight: bold;">Low Stock (26-50%)</h3>
                </div>
                <table class="table-card" style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 10px 15px; text-align: left; color: #27272a;">Equipment</th>
                            <th style="padding: 10px 15px; text-align: center; width: 15%;">Remaining</th>
                            <th style="padding: 10px 15px; text-align: center; width: 15%;">Total</th>
                            <th style="padding: 10px 15px; text-align: center; width: 15%;">Availability %</th>
                            <th style="padding: 10px 15px; text-align: left; width: 25%;">Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowStock as $equipment)
                            @php
                                $res = $equipment->reservations->where('status', 'Approved')->sum('quantity');
                                $unav = $equipment->unavailable->sum('unavailable_quantity');
                                $avail = $equipment->quantity - $res - $unav;
                                $perc = ($equipment->quantity > 0) ? ($avail / $equipment->quantity) * 100 : 0;
                            @endphp
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 10px 15px; font-weight: bold;">{{ $equipment->equipment_name }}</td>
                                <td class="text-yellow" style="padding: 10px 15px; text-align: center; font-weight: 600;">{{ $avail }}</td>
                                <td style="padding: 10px 15px; text-align: center; font-weight: 600;">{{ $equipment->quantity }}</td>
                                <td style="padding: 10px 15px; text-align: center;">{{ round($perc, 1) }}%</td>
                                <td style="padding: 10px 15px;">{{ $equipment->location }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if($outOfStock->count() === 0 && $criticalStock->count() === 0 && $lowStock->count() === 0)
            <div class="alert-success" style="padding: 20px; text-align: center; color: #15803d; border-radius: 10px; font-weight: bold; font-size: 12px;">
                All equipment stock levels are healthy.
            </div>
        @endif
    </div>
</x-filament-panels::page>