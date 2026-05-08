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

        #searchInput {
            width: 100%; 
            height: 40px; 
            padding: 0 12px; 
            margin-bottom: 20px; 
            border: 1px solid #d1d5db; 
            border-radius: 8px; 
            font-size: 13px; 
            background: white; 
            color: #111827; 
            transition: all 0.2s ease-in-out;
            outline: none;
        }

        #searchInput:focus {
            border-color: #fe800d;
            ring: 2px;
            box-shadow: 0 0 0 2px rgba(254, 128, 13, 0.2);
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

        .dark #searchInput {
            background: #27272a !important;
            border-color: #3f3f46 !important;
            color: #ffffff !important;
        }

        .dark #searchInput:focus {
            border-color: #fe800d !important;
            box-shadow: 0 0 0 2px rgba(254, 128, 13, 0.4);
        }
    </style>

    @php
        $currentMonth = request('month');
        $currentYear = request('year', now()->year);
    @endphp

    <div class="filter-container">
        <span class="filter-label">Search Name</span>

        <input
            type="text"
            id="searchInput"
            placeholder="Search borrower, equipment, or supply..."
        >
        
        <div style="display: flex; gap: 30px; flex-wrap: wrap; align-items: flex-end;">
            
            <div style="flex: 1; min-width: 300px;">
                <span class="filter-label" style="margin-bottom: 14px;">Month</span>
                <div style="display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
                    @foreach(range(1, 12) as $m)
                        <button 
                            type="button"
                            onclick="window.location.href = '{{ request()->fullUrlWithQuery(['month' => $m]) }}'"
                            id="month-btn-{{ $m }}"
                            class="filter-btn {{ $currentMonth == $m ? 'active-btn' : 'inactive-btn' }}"
                            style="cursor: pointer;">
                            {{ date('M', mktime(0, 0, 0, $m, 1)) }}
                        </button>
                    @endforeach
            
                    <button
                        type="button"
                        id="all-months-btn"
                        onclick="window.location.href='{{ url()->current() . '?' . http_build_query([
                            'year' => $currentYear,
                        ]) }}'"
                        class="filter-btn {{ is_null($currentMonth) ? 'active-btn' : 'inactive-btn' }}"
                        style="cursor: pointer;"
                    >
                        All Months
                    </button>
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
                <tbody id="equipmentTable">
                    @forelse($returnedEquipment as $item)
                        <tr class="month-row" style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 10px 15px; font-weight: bold;">{{ $item->reserved_by }}</td>
                            <td style="padding: 10px 15px;">{{ $item->equipment?->equipment_name }}</td>
                            <td style="padding: 10px 15px; text-align: center; font-weight: 600;">{{ $item->quantity }}</td>
                            <td class="text-green" style="padding: 10px 15px; font-weight: 600;">{{ $item->updated_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr class="month-row-empty">
                            <td colspan="4" style="padding: 20px; text-align: center; color: #555;">No items returned yet.</td>
                        </tr>
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
                <tbody id="supplyTable">
                    @forelse($returnedSupply as $item)
                        <tr class="month-row" style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 10px 15px; font-weight: bold;">{{ $item->reserved_by }}</td>
                            <td style="padding: 10px 15px;">{{ $item->supply?->item_name }}</td>
                            <td style="padding: 10px 15px; text-align: center; font-weight: 600;">{{ $item->quantity }}</td>
                            <td class="text-green" style="padding: 10px 15px; font-weight: 600;">{{ $item->updated_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr class="month-row-empty">
                            <td colspan="4" style="padding: 20px; text-align: center; color: #555;">No supplies replaced yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const allEquipment = @json($allEquipment);
        const allSupply = @json($allSupply);
    
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const equipmentTable = document.getElementById('equipmentTable');
            const supplyTable = document.getElementById('supplyTable');
    
            searchInput.addEventListener('keyup', function () {
    
                const search = this.value.toLowerCase().trim();

                const monthButtons = document.querySelectorAll('.month-btn');
                const allMonthsBtn = document.getElementById('all-months-btn');

                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('active-btn');
                    btn.classList.add('inactive-btn');
                });
                
                allMonthsBtn.classList.remove('inactive-btn');
                allMonthsBtn.classList.add('active-btn');
    
                if (search === '') {
                    location.reload();
                    return;
                }
    
                const equipmentResults = allEquipment.filter(item =>
                    item.reserved_by?.toLowerCase().includes(search) ||
                    item.equipment?.equipment_name?.toLowerCase().includes(search)
                );
    
                const supplyResults = allSupply.filter(item =>
                    item.reserved_by?.toLowerCase().includes(search) ||
                    item.supply?.item_name?.toLowerCase().includes(search)
                );
    
                equipmentTable.innerHTML = '';
    
                if (equipmentResults.length > 0) {
                    equipmentResults.forEach(item => {
                        equipmentTable.innerHTML += `
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 10px 15px; font-weight: bold;">${item.reserved_by ?? ''}</td>
                                <td style="padding: 10px 15px;">${item.equipment?.equipment_name ?? ''}</td>
                                <td style="padding: 10px 15px; text-align: center; font-weight: 600;">${item.quantity ?? ''}</td>
                                <td class="text-green" style="padding: 10px 15px; font-weight: 600;">
                                    ${new Date(item.updated_at).toLocaleString()}
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    equipmentTable.innerHTML = `
                        <tr>
                            <td colspan="4" style="padding: 20px; text-align: center; color: #555;">
                                No matching equipment found.
                            </td>
                        </tr>
                    `;
                }
    
                supplyTable.innerHTML = '';
    
                if (supplyResults.length > 0) {
                    supplyResults.forEach(item => {
                        supplyTable.innerHTML += `
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 10px 15px; font-weight: bold;">${item.reserved_by ?? ''}</td>
                                <td style="padding: 10px 15px;">${item.supply?.item_name ?? ''}</td>
                                <td style="padding: 10px 15px; text-align: center; font-weight: 600;">${item.quantity ?? ''}</td>
                                <td class="text-green" style="padding: 10px 15px; font-weight: 600;">
                                    ${new Date(item.updated_at).toLocaleString()}
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    supplyTable.innerHTML = `
                        <tr>
                            <td colspan="4" style="padding: 20px; text-align: center; color: #555;">
                                No matching supplies found.
                            </td>
                        </tr>
                    `;
                }
    
            });
    
        });
    </script>
</x-filament-panels::page>