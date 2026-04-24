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

        .table-header-danger, .table-header-warning, .table-header-low, .table-header-success {
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
        .alert-success, .table-header-success, .featured-card   { background: #f0fdf4; border: 1px solid #86efac; color: #15803d; }

        .badge-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            line-height: 1;
            border: 1px solid transparent;
        }

        .badge-equipment {
            background-color: #e0f2fe;
            color: #0369a1;
        }

        .badge-supply {
            background-color: #fef3c7;
            color: #92400e;
        }

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
        .dark .alert-success, .dark .table-header-success, .dark .featured-card { background: #064e3b !important; border-color: #10b981 !important; color: #ecfdf5 !important; }

        .dark .badge-equipment {
            background-color: #0369a1;
            color: #e0f2fe;
        }

        .dark .badge-supply {
            background-color: #92400e;
            color: #fef3c7;
        }
    </style>

    @php
        $currentMonth = request('month');
        $currentYear = request('year', now()->year);

        // Merge Current
        $mergedCurrent = collect($borrowedEquipment)->map(fn($i) => (object)[
            'borrower' => $i->reserved_by,
            'item' => $i->equipment?->equipment_name,
            'qty' => $i->quantity,
            'date' => $i->end_date,
            'type' => 'Equipment'
        ])->concat(collect($borrowedSupply)->map(fn($i) => (object)[
            'borrower' => $i->reserved_by,
            'item' => $i->supply?->item_name,
            'qty' => $i->quantity,
            'date' => $i->end_date,
            'type' => 'Supply'
        ]))->sortBy('date');

        // Merge Overdue
        $mergedOverdue = collect($overdueEquipment)->map(fn($i) => (object)[
            'borrower' => $i->reserved_by,
            'item' => $i->equipment?->equipment_name,
            'qty' => $i->quantity,
            'date' => $i->end_date,
            'type' => 'Equipment'
        ])->concat(collect($overdueSupply)->map(fn($i) => (object)[
            'borrower' => $i->reserved_by,
            'item' => $i->supply?->item_name,
            'qty' => $i->quantity,
            'date' => $i->end_date,
            'type' => 'Supply'
        ]))->sortBy('date');
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

    <div>
        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 20px; color: #374151; padding-left: 10px;">Returned Equipment and Supplies</h2>

        {{-- RETURNED EQUIPMENT --}}
        <div style="border-radius: 10px; overflow: hidden; margin-bottom: 25px;">
            <div class="table-header-success">
                <h3 class="text-green" style="font-size: 13px; font-weight: bold;">Returned Equipment (Completed)</h3>
            </div>
            <table class="table-card" style="width: 100%; border-collapse: collapse; font-size: 11px;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 10px 15px; text-align: left; color: #374151;">Borrower</th>
                        <th style="padding: 10px 15px; text-align: left; color: #374151;">Equipment</th>
                        <th style="padding: 10px 15px; text-align: center; width: 10%;">Qty</th>
                        <th style="padding: 10px 15px; text-align: left; width: 25%;">Returned Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returnedEquipment as $item)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 10px 15px; font-weight: bold;">{{ $item->reserved_by }}</td>
                            <td style="padding: 10px 15px;">{{ $item->equipment?->equipment_name }}</td>
                            <td style="padding: 10px 15px; text-align: center; font-weight: 600;">{{ $item->quantity }}</td>
                            <td class="text-green" style="padding: 10px 15px; font-weight: 600;">{{ $item->updated_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="padding: 20px; text-align: center; color: #555;">No items returned yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- RETURNED SUPPLIES --}}
        <div style="border-radius: 10px; overflow: hidden; margin-bottom: 25px;">
            <div class="table-header-success">
                <h3 class="text-green" style="font-size: 13px; font-weight: bold;">Replaced Supplies (Completed)</h3>
            </div>
            <table class="table-card" style="width: 100%; border-collapse: collapse; font-size: 11px;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 10px 15px; text-align: left; color: #374151;">Borrower</th>
                        <th style="padding: 10px 15px; text-align: left; color: #374151;">Supply</th>
                        <th style="padding: 10px 15px; text-align: center; width: 10%;">Qty</th>
                        <th style="padding: 10px 15px; text-align: left; width: 25%;">Replacement Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returnedSupply as $item)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 10px 15px; font-weight: bold;">{{ $item->reserved_by }}</td>
                            <td style="padding: 10px 15px;">{{ $item->supply?->item_name }}</td>
                            <td style="padding: 10px 15px; text-align: center; font-weight: 600;">{{ $item->quantity }}</td>
                            <td class="text-green" style="padding: 10px 15px; font-weight: 600;">{{ $item->updated_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="padding: 20px; text-align: center; color: #555;">No supplies replaced yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 20px; color: #374151; padding-left: 10px;">Borrowed Equipment and Supplies</h2>

        {{-- CURRENTLY BORROWED --}}
        <div style="border-radius: 10px; overflow: hidden; margin-bottom: 25px;">
            <div class="table-header-warning">
                <h3 class="text-orange" style="font-size: 13px; font-weight: bold;">Currently Borrowed Items</h3>
            </div>
            <table class="table-card" style="width: 100%; border-collapse: collapse; font-size: 11px;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 10px 15px; text-align: left; color: #374151;">Borrower</th>
                        <th style="padding: 10px 15px; text-align: left; color: #374151;">Item & Type</th>
                        <th style="padding: 10px 15px; text-align: center; width: 10%;">Qty</th>
                        <th style="padding: 10px 15px; text-align: left; width: 25%;">Expected Return</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mergedCurrent as $item)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 10px 15px; font-weight: bold;">{{ $item->borrower }}</td>
                            <td style="padding: 10px 15px;">
                                <div style="font-weight: 500; margin-bottom: 4px;">{{ $item->item }}</div>
                                <span style="background: {{ $item->type === 'Equipment' ? '#e0f2fe' : '#fef3c7' }}; color: {{ $item->type === 'Equipment' ? '#0369a1' : '#92400e' }}; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 600; text-transform: uppercase;">
                                    {{ $item->type }}
                                </span>
                            </td>
                            <td style="padding: 10px 15px; text-align: center; font-weight: 600;">{{ $item->qty }}</td>
                            <td class="text-orange" style="padding: 10px 15px; font-weight: 600;">{{ $item->date->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="padding: 20px; text-align: center; color: #555;">No active borrowed items.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 20px; color: #374151; padding-left: 10px;">Overdue Equipment and Supplies</h2>

        {{-- OVERDUE --}}
        <div style="border-radius: 10px; overflow: hidden;">
            <div class="table-header-danger">
                <h3 class="text-red" style="font-size: 13px; font-weight: bold;">Overdue Items</h3>
            </div>
            <table class="table-card" style="width: 100%; border-collapse: collapse; font-size: 11px;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 10px 15px; text-align: left; color: #374151;">Borrower</th>
                        <th style="padding: 10px 15px; text-align: left; color: #374151;">Item & Type</th>
                        <th style="padding: 10px 15px; text-align: center; width: 10%;">Qty</th>
                        <th style="padding: 10px 15px; text-align: left; width: 15%;">Deadline</th>
                        <th style="padding: 10px 15px; text-align: right; width: 10%;">Late</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mergedOverdue as $item)
                        @php 
                            $daysLate = $item->date->startOfDay()->diffInDays(now()->startOfDay()); 
                        @endphp
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 10px 15px; font-weight: bold;">{{ $item->borrower }}</td>
                            <td style="padding: 10px 15px; vertical-align: middle;">
                                <div style="font-weight: 500; margin-bottom: 4px;">{{ $item->item }}</div>
                                <span class="badge-item {{ $item->type === 'Equipment' ? 'badge-equipment' : 'badge-supply' }}">
                                    {{ $item->type }}
                                </span>
                            </td>
                            <td style="padding: 10px 15px; text-align: center; font-weight: 600;">{{ $item->qty }}</td>
                            <td style="padding: 10px 15px; font-weight: 600;">{{ $item->date->format('M d, Y') }}</td>
                            <td class="text-red" style="padding: 10px 15px; text-align: right; font-weight: 600;">
                                {{ (int) $daysLate }} Days
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="padding: 20px; text-align: center; color: #555;">No overdue items.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($this->getFooterActions())
            <div class="flex justify-end gap-3 mt-6">
                <x-filament::actions :actions="$this->getFooterActions()" />
            </div>
        @endif
    </div>
</x-filament-panels::page>