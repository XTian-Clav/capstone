<style>
    .text-blue { color: #013267; }
    .text-red { color: #991b1b; }
    .text-green { color: #15803d; }
    .text-orange { color: #c2410c; }
    .text-yellow { color: #ca8a04; }
</style>

<div style="font-family: sans-serif; overflow: auto;">
    <h2 style="font-size: 12px; font-weight: bold; text-transform: uppercase; margin-bottom: 15px; color: #333; background-color: #fff7ed; padding: 8px 12px; border-left: 4px solid #fe800d;">
        Reservation Status Summary
    </h2>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 12px;">
        <tr>
            <th style="border-bottom: 1px solid #eee; padding: 10px; text-align: left; color: #666; width: 25%;">Completed</th>
            <th style="border-bottom: 1px solid #eee; padding: 10px; text-align: left; color: #666; width: 25%;">Approved</th>
            <th style="border-bottom: 1px solid #eee; padding: 10px; text-align: left; color: #666; width: 25%;">Pending</th>
            <th style="border-bottom: 1px solid #eee; padding: 10px; text-align: left; color: #666; width: 25%;">Rejected</th>
        </tr>
        <tr>
            <td style="padding: 15px 10px;">
                <div class="text-blue" style="font-size: 16px; font-weight: bold;">{{ $totalCompleted }}</div>
                <div style="color: #999; font-size: 11px; text-transform: uppercase;">Successfully returned</div>
            </td>
            <td style="padding: 15px 10px;">
                <div class="text-green" style="font-size: 16px; font-weight: bold;">{{ $totalApproved }}</div>
                <div style="color: #999; font-size: 11px; text-transform: uppercase;">Active reservations</div>
            </td>
            <td style="padding: 15px 10px;">
                <div class="text-yellow" style="font-size: 16px; font-weight: bold;">{{ $totalPending }}</div>
                <div style="color: #999; font-size: 11px; text-transform: uppercase;">Awaiting action</div>
            </td>
            <td style="padding: 15px 10px;">
                <div class="text-red" style="font-size: 16px; font-weight: bold;">{{ $totalRejected }}</div>
                <div style="color: #999; font-size: 11px; text-transform: uppercase;">Cancelled requests</div>
            </td>
        </tr>
    </table>

    <h2 style="font-size: 12px; font-weight: bold; text-transform: uppercase; margin-bottom: 15px; color: #333; background-color: #fff7ed; padding: 8px 12px; border-left: 4px solid #fe800d;">
        Equipment Inventory Overview
    </h2>
    
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 12px;">
        <tr>
            <th style="border-bottom: 1px solid #eee; padding: 10px; text-align: left; color: #666; width: 25%;">Total Equipment</th>
            <th style="border-bottom: 1px solid #eee; padding: 10px; text-align: left; color: #666; width: 25%;">Available</th>
            <th style="border-bottom: 1px solid #eee; padding: 10px; text-align: left; color: #666; width: 25%;">Reserved</th>
            <th style="border-bottom: 1px solid #eee; padding: 10px; text-align: left; color: #666; width: 25%;">Unavailable</th>
        </tr>
        <tr>
            <td style="padding: 15px 10px;">
                <div class="text-blue" style="font-size: 16px; font-weight: bold;">{{ $totalEquipment }}</div>
                <div style="color: #999; font-size: 11px; text-transform: uppercase;">Total units</div>
            </td>
            <td style="padding: 15px 10px;">
                <div class="text-green" style="font-size: 16px; font-weight: bold;">{{ $totalAvailable }}</div>
                <div style="color: #999; font-size: 11px; text-transform: uppercase;">Ready for use</div>
            </td>
            <td style="padding: 15px 10px;">
                <div class="text-yellow" style="font-size: 16px; font-weight: bold;">{{ $totalReserved }}</div>
                <div style="color: #999; font-size: 11px; text-transform: uppercase;">Currently borrowed</div>
            </td>
            <td style="padding: 15px 10px;">
                <div class="text-red" style="font-size: 16px; font-weight: bold;">{{ $totalUnavailable }}</div>
                <div style="color: #999; font-size: 11px; text-transform: uppercase;">Out of service</div>
            </td>
        </tr>
    </table>

    @if($mostBorrowed && $mostBorrowed->borrow_count > 0)
        <div style="border: 1px solid #eee; margin-bottom: 25px; font-size: 12px; border-radius: 4px;">
            <div class="text-green" style="background-color: #f0fdf4; padding: 8px 12px; font-weight: bold; border-bottom: 1px solid #eee; text-transform: uppercase;">
                Most Frequently Borrowed
            </div>
            <div style="padding: 12px; color: #444; line-height: 1.4;">
                The <strong>{{ $mostBorrowed->equipment_name }}</strong> is your most requested equipment 
                with a total of <strong class="text-green">{{ $mostBorrowed->borrow_count }}</strong> approved reservations.
            </div>
        </div>
    @endif

    <h2 style="font-size: 12px; font-weight: bold; text-transform: uppercase; margin-bottom: 15px; color: #333; background-color: #fff7ed; padding: 8px 12px; border-left: 4px solid #fe800d;">
        Equipment Details
    </h2>
    <table style="width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 25px;">
        <thead>
            <tr style="background-color: #fcfcfc;">
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: left; color: #555;">Equipment</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: left; color: #555;">Location</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: center; color: #555;">Total</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: center; color: #555;">Avail</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: center; color: #555;">Res</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: center; color: #555;">Unavail</th>
            </tr>
        </thead>
        <tbody>
            @foreach($equipments as $equipment)
                @php
                    $reserved = $equipment->reservations->where('status', 'Approved')->sum('quantity');
                    $unavailable = $equipment->unavailable->sum('unavailable_quantity');
                    $available = $equipment->quantity - $reserved - $unavailable;
                @endphp
                <tr>
                    <td style="border-bottom: 1px solid #f5f5f5; padding: 10px; font-weight: bold;">{{ $equipment->equipment_name }}</td>
                    <td style="border-bottom: 1px solid #f5f5f5; padding: 10px; color: #666;">{{ $equipment->location }}</td>
                    <td style="border-bottom: 1px solid #f5f5f5; padding: 10px; text-align: center; font-weight: bold;">{{ $equipment->quantity }}</td>
                    <td class="text-green" style="border-bottom: 1px solid #f5f5f5; padding: 10px; text-align: center; font-weight: bold;">{{ $available }}</td>
                    <td class="text-yellow" style="border-bottom: 1px solid #f5f5f5; padding: 10px; text-align: center; font-weight: bold;">{{ $reserved }}</td>
                    <td class="text-red" style="border-bottom: 1px solid #f5f5f5; padding: 10px; text-align: center; font-weight: bold;">{{ $unavailable }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2 style="font-size: 12px; font-weight: bold; text-transform: uppercase; margin-bottom: 15px; color: #333; background-color: #fff7ed; padding: 8px 12px; border-left: 4px solid #fe800d;">
        Equipment Stock Alerts
    </h2>
    
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px;">
        <tr>
            <td style="padding-right: 10px; width: 33%;">
                <div style="background: #fef2f2; padding: 12px; border: 1px solid #fca5a5; border-radius: 4px; text-align: center;">
                    <div style="font-size: 10px; font-weight: bold; color: #991b1b; text-transform: uppercase;">Out of Stock</div>
                    <div class="text-red" style="font-size: 18px; font-weight: bold;">{{ $outOfStock->count() }}</div>
                </div>
            </td>
            <td style="padding: 0 5px; width: 33%;">
                <div style="background: #fff7ed; padding: 12px; border: 1px solid #fdba74; border-radius: 4px; text-align: center;">
                    <div style="font-size: 10px; font-weight: bold; color: #9a3412; text-transform: uppercase;">Critical Stock</div>
                    <div class="text-orange" style="font-size: 18px; font-weight: bold;">{{ $criticalStock->count() }}</div>
                </div>
            </td>
            <td style="padding-left: 10px; width: 34%;">
                <div style="background: #fefce8; padding: 12px; border: 1px solid #facc15; border-radius: 4px; text-align: center;">
                    <div style="font-size: 10px; font-weight: bold; color: #854d0e; text-transform: uppercase;">Low Stock</div>
                    <div class="text-yellow" style="font-size: 18px; font-weight: bold;">{{ $lowStock->count() }}</div>
                </div>
            </td>
        </tr>
    </table>

    @if($outOfStock->count() > 0 || $criticalStock->count() > 0 || $lowStock->count() > 0)
        <table style="width: 100%; border-collapse: collapse; font-size: 12px; border: 1px solid #eee;">
            <thead>
                <tr style="background-color: #f9fafb;">
                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #eee; color: #666; width: 45%;">Equipment Name</th>
                    <th style="padding: 10px; text-align: center; border-bottom: 1px solid #eee; color: #666; width: 20%;">Available / Total</th>
                    <th style="padding: 10px; text-align: center; border-bottom: 1px solid #eee; color: #666; width: 10%;">Level</th>
                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #eee; color: #666; width: 25%;">Location</th>
                </tr>
            </thead>
            <tbody>
                @foreach($outOfStock as $equipment)
                    <tr style="border-bottom: 1px solid #f5f5f5;">
                        <td style="padding: 10px;">
                            <span style="background: #991b1b; color: white; padding: 2px 6px; border-radius: 3px; font-weight: bold; font-size: 8px; text-transform: uppercase; margin-right: 5px;">Empty</span>
                            <strong style="vertical-align: middle; font-size: 12px;">{{ $equipment->equipment_name }}</strong>
                        </td>
                        <td style="padding: 10px; text-align: center; font-weight: bold;" class="text-red">0 / {{ $equipment->quantity }}</td>
                        <td style="padding: 10px; text-align: center; font-weight: bold;" class="text-red">0%</td>
                        <td style="padding: 10px; color: #666;">{{ $equipment->location }}</td>
                    </tr>
                @endforeach
    
                @foreach($criticalStock as $equipment)
                    <tr style="border-bottom: 1px solid #f5f5f5;">
                        <td style="padding: 10px;">
                            <span style="background: #c2410c; color: white; padding: 2px 6px; border-radius: 3px; font-weight: bold; font-size: 8px; text-transform: uppercase; margin-right: 5px;">Critical</span>
                            <strong style="vertical-align: middle;">{{ $equipment->equipment_name }}</strong>
                        </td>
                        <td style="padding: 10px; text-align: center; font-weight: bold;">{{ $equipment->available }} / {{ $equipment->quantity }}</td>
                        <td style="padding: 10px; text-align: center; font-weight: bold;" class="text-orange">{{ round($equipment->availability_percentage, 0) }}%</td>
                        <td style="padding: 10px; color: #666;">{{ $equipment->location }}</td>
                    </tr>
                @endforeach
    
                @foreach($lowStock as $equipment)
                    <tr style="border-bottom: 1px solid #f5f5f5;">
                        <td style="padding: 10px;">
                            <span style="background: #ca8a04; color: white; padding: 2px 6px; border-radius: 3px; font-weight: bold; font-size: 8px; text-transform: uppercase; margin-right: 5px;">Low</span>
                            <strong style="vertical-align: middle; font-size: 11px;">{{ $equipment->equipment_name }}</strong>
                        </td>
                        <td style="padding: 10px; text-align: center; font-weight: bold;">{{ $equipment->available }} / {{ $equipment->quantity }}</td>
                        <td style="padding: 10px; text-align: center; font-weight: bold;" class="text-yellow">{{ round($equipment->availability_percentage, 0) }}%</td>
                        <td style="padding: 10px; color: #666;">{{ $equipment->location }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="padding: 15px; text-align: center; background-color: #f0fdf4; border: 1px solid #dcfce7; color: #15803d; border-radius: 4px; font-weight: bold; font-size: 11px; text-transform: uppercase;">
            All equipment stock levels are healthy.
        </div>
    @endif
</div>