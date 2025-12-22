<style>
    .text-blue { color: #013267; }
    .text-red { color: #991b1b; }
    .text-green { color: #15803d; }
</style>

<div style="overflow: auto;">
    <h2 style="font-size: 12px; font-weight: bold; text-transform: uppercase; margin-bottom: 15px; color: #333; background-color: #fff7ed; padding: 8px 12px; border-left: 4px solid #fe800d;">Room Performance Summary</h2>
    
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 12px;">
        <tr>
            <th style="border-bottom: 1px solid #eee; padding: 10px; text-align: left; color: #666; width: 33%;">Total Revenue (Approved)</th>
            <th style="border-bottom: 1px solid #eee; padding: 10px; text-align: left; color: #666; width: 33%;">Lost Opportunity</th>
            <th style="border-bottom: 1px solid #eee; padding: 10px; text-align: left; color: #666; width: 34%;">Total Room Units</th>
        </tr>
        <tr>
            <td style="padding: 15px 10px;">
                <div class="text-green" style="font-size: 16px; font-weight: bold;"><span style="font-family: 'DejaVu Sans', sans-serif;">&#8369;</span>{{ number_format($totalRevenue, 2) }}</div>
                <div style="color: #999; font-size: 11px; text-transform: uppercase;">Actual earnings</div>
            </td>
            <td style="padding: 15px 10px;">
                <div class="text-red" style="font-size: 16px; font-weight: bold;"><span style="font-family: 'DejaVu Sans', sans-serif;">&#8369;</span>{{ number_format($totalLostRevenue, 2) }}</div>
                <div style="color: #999; font-size: 11px; text-transform: uppercase;">From rejected requests</div>
            </td>
            <td style="padding: 15px 10px;">
                <div class="text-blue   " style="font-size: 16px; font-weight: bold;">{{ $totalRooms }} Rooms</div>
                <div style="color: #999; font-size: 11px; text-transform: uppercase;">Active rooms</div>
            </td>
        </tr>
    </table>

    <h2 style="font-size: 12px; font-weight: bold; text-transform: uppercase; margin-bottom: 15px; color: #333; background-color: #fff7ed; padding: 8px 12px; border-left: 4px solid #fe800d;">Utilization & Revenue Analysis</h2>
    <table style="width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 25px;">
        <thead>
            <tr style="background-color: #fcfcfc;">
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: left; color: #555;">Room Details</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: center; color: #555;">Capacity</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: center; color: #555;">Approved</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: center; color: #555;">Rejected</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: left; color: #555;">Revenue / Loss</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: left; color: #555;">Popularity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rooms as $room)
                <tr>
                    <td style="border-bottom: 1px solid #f5f5f5; padding: 10px;">
                        <div style="font-weight: bold; color: #333;">{{ $room->room_type }}</div>
                        <div style="color: #888; font-size: 11px;">{{ $room->location }}</div>
                    </td>
                    <td style="border-bottom: 1px solid #f5f5f5; padding: 10px; text-align: center;">{{ $room->capacity }}</td>
                    <td style="border-bottom: 1px solid #f5f5f5; font-weight: bold; padding: 10px; text-align: center;">{{ $room->approved_count }}</td>
                    <td class="text-green" style="border-bottom: 1px solid #f5f5f5; font-weight: bold; padding: 10px; text-align: center;">{{ $room->rejected_count }}</td>
                    <td class="text-red" style="border-bottom: 1px solid #f5f5f5; padding: 10px;">
                        <div class="text-green" style="font-weight: bold;">+{{ number_format($room->revenue, 0) }}</div>
                        <div class="text-red">-{{ number_format($room->lost_revenue, 0) }}</div>
                    </td>
                    <td style="border-bottom: 1px solid #f5f5f5; padding: 10px; font-weight: bold; color: #666;">
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

    <div style="width: 100%;">
        <div style="border: 1px solid #eee; margin-bottom: 12px; font-size: 12px; border-radius: 4px;">
            <div class="text-green" style="background-color: #f0fdf4; padding: 8px 12px; font-weight: bold; border-bottom: 1px solid #eee;">Most Popular Room</div>
            <div style="padding: 12px; color: #444; line-height: 1.4;">
                @php $topRoom = $rooms->sortByDesc('approved_count')->first(); @endphp
                @if($topRoom)
                    The <strong>{{ $topRoom->room_type }}</strong> is your most successful asset with 
                    <strong>{{ $topRoom->approved_count }}</strong> confirmed bookings, generating 
                    <strong class="text-green"><span style="font-family: 'DejaVu Sans', sans-serif;">&#8369;</span>{{ number_format($topRoom->revenue, 2) }}</strong>.
                @endif
            </div>
        </div>

        <div style="border: 1px solid #eee; font-size: 12px; border-radius: 4px;">
            <div class="text-red" style="background-color: #fef2f2; padding: 8px 12px; font-weight: bold; border-bottom: 1px solid #eee;">Revenue Lost</div>
            <div style="padding: 12px; color: #444; line-height: 1.4;">
                @php $mostRejected = $rooms->sortByDesc('lost_revenue')->first(); @endphp
                @if($mostRejected && $mostRejected->lost_revenue > 0)
                    The <strong>{{ $mostRejected->room_type }}</strong> has the highest lost opportunity. You missed out on 
                    <strong class="text-red"><span style="font-family: 'DejaVu Sans', sans-serif;">&#8369;</span>{{ number_format($mostRejected->lost_revenue, 2) }}</strong> due to 
                    <strong>{{ $mostRejected->rejected_count }}</strong> rejections.
                @else
                    No significant revenue loss from rejected bookings.
                @endif
            </div>
        </div>
    </div>
</div>