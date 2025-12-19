<x-filament-panels::page>
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold mb-4">Room Performance Summary</h2>
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-blue-100 p-4 rounded-lg border-b-4 border-blue-500">
                    <h3 class="font-bold text-blue-800">Total Revenue (Approved)</h3>
                    <p class="text-3xl font-bold text-blue-900">₱{{ number_format($totalRevenue, 2) }}</p>
                    <p class="text-sm text-blue-700">Actual earnings</p>
                </div>
                <div class="bg-red-100 p-4 rounded-lg border-b-4 border-red-500">
                    <h3 class="font-bold text-red-800">Lost Opportunity</h3>
                    <p class="text-3xl font-bold text-red-900">₱{{ number_format($totalLostRevenue, 2) }}</p>
                    <p class="text-sm text-red-700">From rejected requests</p>
                </div>
                <div class="bg-purple-100 p-4 rounded-lg border-b-4 border-purple-500">
                    <h3 class="font-bold text-purple-800">Total Room Units</h3>
                    <p class="text-3xl font-bold text-purple-900">{{ $totalRooms }}</p>
                    <p class="text-sm text-purple-700">Active rooms in system</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
            <div class="px-6 py-4 bg-gray-50 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Utilization & Revenue Analysis</h3>
                <span class="text-xs font-medium bg-gray-200 px-2 py-1 rounded text-gray-600 uppercase tracking-wider">Yield Performance</span>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Room Details</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Capacity</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase text-center">Approved</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase text-center">Rejected</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Revenue / Loss</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Popularity</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($rooms as $room)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $room->room_type }}</div>
                                <div class="text-xs text-gray-500">{{ $room->location }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $room->capacity }}</td>
                            <td class="px-6 py-4 text-center text-sm font-semibold text-green-600">{{ $room->approved_count }}</td>
                            <td class="px-6 py-4 text-center text-sm font-semibold text-red-400">{{ $room->rejected_count }}</td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-bold text-green-700">Earnings: ₱{{ number_format($room->revenue, 2) }}</div>
                                <div class="text-xs text-red-500">Loss: ₱{{ number_format($room->lost_revenue, 2) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $maxBookings = $rooms->max('total_requests') ?: 1;
                                    $popularity = ($room->total_requests / $maxBookings) * 100;
                                @endphp
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-1.5 mr-2 max-w-[80px]">
                                        <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $popularity }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ round($popularity) }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                <div class="flex items-center mb-2">
                    <span class="text-xl mr-2">🏆</span>
                    <h4 class="font-bold text-gray-800">Top Performer</h4>
                </div>
                @php $topRoom = $rooms->sortByDesc('approved_count')->first(); @endphp
                @if($topRoom)
                    <p class="text-sm text-gray-600">
                        The <span class="font-bold text-gray-900">{{ $topRoom->room_type }}</span> is your most successful asset with 
                        <span class="font-bold text-green-600">{{ $topRoom->approved_count }}</span> confirmed bookings, generating 
                        <span class="font-bold text-gray-900">₱{{ number_format($topRoom->revenue, 2) }}</span>.
                    </p>
                @endif
            </div>

            <div class="bg-white p-6 rounded-lg shadow border-l-4 border-red-500">
                <div class="flex items-center mb-2">
                    <span class="text-xl mr-2">💸</span>
                    <h4 class="font-bold text-gray-800">Revenue Leakage</h4>
                </div>
                @php $mostRejected = $rooms->sortByDesc('lost_revenue')->first(); @endphp
                @if($mostRejected && $mostRejected->lost_revenue > 0)
                    <p class="text-sm text-gray-600">
                        The <span class="font-bold text-gray-900">{{ $mostRejected->room_type }}</span> has the highest lost opportunity. You missed out on 
                        <span class="font-bold text-red-600">₱{{ number_format($mostRejected->lost_revenue, 2) }}</span> due to 
                        <span class="font-bold text-gray-900">{{ $mostRejected->rejected_count }}</span> rejections.
                    </p>
                @else
                    <p class="text-sm text-gray-600">
                        Excellent! There is currently no significant revenue leakage from rejected bookings.
                    </p>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>