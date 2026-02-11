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
                <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                    @foreach(range(1, 12) as $m)
                        <a href="{{ request()->fullUrlWithQuery(['month' => $m]) }}" 
                        class="filter-btn {{ $currentMonth == $m ? 'active-btn' : 'inactive-btn' }}">
                            {{ date('M', mktime(0, 0, 0, $m, 1)) }}
                        </a>
                    @endforeach

                    <a href="{{ request()->fullUrlWithQuery(['month' => null]) }}" 
                    class="filter-btn {{ is_null($currentMonth) ? 'active-btn' : 'inactive-btn' }}"
                    style="font-weight: bold;">
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
                                :color="$currentYear == $y ? 'warning' : 'gray'"
                            >
                                {{ $y }}
                            </x-filament::dropdown.list.item>
                        @endforeach
                    </x-filament::dropdown.list>
                </x-filament::dropdown>
            </div>
        </div>
    </div>

    <div>
        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 20px; color: #374151; padding-left: 10px;">Returned Equipment and Supplies</h2>

        {{-- RETURNED EQUIPMENT --}}
        <div style="background: white; border-radius: 10px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 25px;">
            <div style="background: #f0fdf4; padding: 10px 15px; border-bottom: 1px solid #dcfce7;">
                <h3 class="text-green" style="font-size: 13px; font-weight: bold;">Returned Equipment (Completed)</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
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
                        <tr><td colspan="4" style="padding: 20px; text-align: center; color: #666;">No items returned yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- RETURNED SUPPLIES --}}
        <div style="background: white; border-radius: 10px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 25px;">
            <div style="background: #f0fdf4; padding: 10px 15px; border-bottom: 1px solid #dcfce7;">
                <h3 class="text-green" style="font-size: 13px; font-weight: bold;">Replaced Supplies (Completed)</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
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
                        <tr><td colspan="4" style="padding: 20px; text-align: center; color: #666;">No supplies replaced yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 20px; color: #374151; padding-left: 10px;">Borrowed Equipment and Supplies</h2>

        {{-- CURRENTLY BORROWED --}}
        <div style="background: white; border-radius: 10px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 25px;">
            <div style="background: #fff7ed; padding: 10px 15px; border-bottom: 1px solid #ffedd5;">
                <h3 class="text-orange" style="font-size: 13px; font-weight: bold;">Currently Borrowed Items</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
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
                        <tr><td colspan="4" style="padding: 20px; text-align: center; color: #666;">No active borrowed items.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 20px; color: #374151; padding-left: 10px;">Overdue Equipment and Supplies</h2>

        {{-- OVERDUE --}}
        <div style="background: white; border-radius: 10px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 40px;">
            <div style="background: #fef2f2; padding: 10px 15px; border-bottom: 1px solid #fee2e2;">
                <h3 class="text-red" style="font-size: 13px; font-weight: bold;">Overdue Items</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
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
                            <td style="padding: 10px 15px;">
                                <div style="font-weight: 500; margin-bottom: 4px;">{{ $item->item }}</div>
                                <span style="background: {{ $item->type === 'Equipment' ? '#e0f2fe' : '#fef3c7' }}; color: {{ $item->type === 'Equipment' ? '#0369a1' : '#92400e' }}; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 600; text-transform: uppercase;">
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
                        <tr><td colspan="5" style="padding: 20px; text-align: center; color: #666;">No overdue items.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>