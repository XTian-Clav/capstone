<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Inventory Overview Summary Cards -->
        <div>
            <h2 class="text-2xl font-bold mb-4">Equipment Inventory Overview</h2>
            <div class="grid grid-cols-4 gap-4">
                <div class="bg-blue-100 p-4 rounded-lg">
                    <h3 class="font-bold text-blue-800">Total Equipment</h3>
                    <p class="text-3xl font-bold text-blue-900">{{ $totalEquipment }}</p>
                </div>
                <div class="bg-green-100 p-4 rounded-lg">
                    <h3 class="font-bold text-green-800">Available</h3>
                    <p class="text-3xl font-bold text-green-900">{{ $totalAvailable }}</p>
                </div>
                <div class="bg-yellow-100 p-4 rounded-lg">
                    <h3 class="font-bold text-yellow-800">Reserved</h3>
                    <p class="text-3xl font-bold text-yellow-900">{{ $totalReserved }}</p>
                </div>
                <div class="bg-red-100 p-4 rounded-lg">
                    <h3 class="font-bold text-red-800">Unavailable</h3>
                    <p class="text-3xl font-bold text-red-900">{{ $totalUnavailable }}</p>
                </div>
            </div>
        </div>

        @if($mostBorrowed && $mostBorrowed->borrow_count > 0)
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-indigo-500 overflow-hidden relative">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">🏆 Most Frequently Borrowed</h4>
                        <p class="text-2xl font-black text-gray-900 mt-1">{{ $mostBorrowed->equipment_name }}</p>
                        <p class="text-sm text-gray-600 mt-1">
                            This item has been requested in <span class="font-bold text-indigo-600">{{ $mostBorrowed->borrow_count }}</span> unique approved reservations.
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-gray-400 uppercase">Usage Frequency</span>
                        <div class="flex items-end justify-end gap-1 mt-2">
                            <div class="w-2 bg-indigo-200 h-4 rounded-t"></div>
                            <div class="w-2 bg-indigo-300 h-6 rounded-t"></div>
                            <div class="w-2 bg-indigo-600 h-10 rounded-t"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Detailed Inventory Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b">
                <h3 class="text-lg font-semibold">Equipment Details</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Equipment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Property No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Qty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Available</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reserved</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unavailable</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($equipments as $equipment)
                        @php
                            $reserved = $equipment->reservations->where('status', 'Approved')->sum('quantity');
                            $unavailable = $equipment->unavailable->sum('unavailable_quantity');
                            $available = $equipment->quantity - $reserved - $unavailable;
                        @endphp
                        <tr>
                            <td class="px-6 py-4 font-medium">{{ $equipment->equipment_name }}</td>
                            <td class="px-6 py-4">{{ $equipment->property_no }}</td>
                            <td class="px-6 py-4">{{ $equipment->location }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $equipment->quantity }}</td>
                            <td class="px-6 py-4 text-green-600 font-semibold">{{ $available }}</td>
                            <td class="px-6 py-4 text-yellow-600">{{ $reserved }}</td>
                            <td class="px-6 py-4 text-red-600">{{ $unavailable }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Low Stock Alert Section -->
        <div>
            <h2 class="text-2xl font-bold mb-4">Low Stock Alerts</h2>
            
            <!-- Alert Summary Cards -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-red-100 border-2 border-red-300 p-4 rounded-lg">
                    <h3 class="font-bold text-red-800">Out of Stock</h3>
                    <p class="text-3xl font-bold text-red-900">{{ $outOfStock->count() }}</p>
                    <p class="text-sm text-red-700">Immediate action required</p>
                </div>
                <div class="bg-orange-100 border-2 border-orange-300 p-4 rounded-lg">
                    <h3 class="font-bold text-orange-800">Critical Stock</h3>
                    <p class="text-3xl font-bold text-orange-900">{{ $criticalStock->count() }}</p>
                    <p class="text-sm text-orange-700">≤20% available</p>
                </div>
                <div class="bg-yellow-100 border-2 border-yellow-300 p-4 rounded-lg">
                    <h3 class="font-bold text-yellow-800">Low Stock</h3>
                    <p class="text-3xl font-bold text-yellow-900">{{ $lowStock->count() }}</p>
                    <p class="text-sm text-yellow-700">21-50% available</p>
                </div>
            </div>

            <!-- Out of Stock -->
            @if($outOfStock->count() > 0)
                <div class="bg-white rounded-lg shadow p-6 mb-4">
                    <h3 class="text-lg font-bold text-red-800 mb-4">🚨 Out of Stock</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Equipment</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Property No</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total Qty</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reserved</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unavailable</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($outOfStock as $equipment)
                                <tr class="bg-red-50">
                                    <td class="px-4 py-3 font-medium">{{ $equipment->equipment_name }}</td>
                                    <td class="px-4 py-3">{{ $equipment->property_no }}</td>
                                    <td class="px-4 py-3">{{ $equipment->quantity }}</td>
                                    <td class="px-4 py-3 text-yellow-600">{{ $equipment->reserved }}</td>
                                    <td class="px-4 py-3 text-red-600">{{ $equipment->unavailable_qty }}</td>
                                    <td class="px-4 py-3">{{ $equipment->location }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- Critical Stock -->
            @if($criticalStock->count() > 0)
                <div class="bg-white rounded-lg shadow p-6 mb-4">
                    <h3 class="text-lg font-bold text-orange-800 mb-4">⚠️ Critical Stock (≤20%)</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Equipment</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Property No</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Available</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Availability %</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($criticalStock as $equipment)
                                <tr class="bg-orange-50">
                                    <td class="px-4 py-3 font-medium">{{ $equipment->equipment_name }}</td>
                                    <td class="px-4 py-3">{{ $equipment->property_no }}</td>
                                    <td class="px-4 py-3 text-orange-600 font-semibold">{{ $equipment->available }}</td>
                                    <td class="px-4 py-3">{{ $equipment->quantity }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <div class="w-full bg-gray-200 rounded-full h-2 mr-2 max-w-[100px]">
                                                <div class="bg-orange-600 h-2 rounded-full" style="width: {{ $equipment->availability_percentage }}%"></div>
                                            </div>
                                            <span class="text-sm whitespace-nowrap">{{ round($equipment->availability_percentage, 1) }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">{{ $equipment->location }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- Low Stock -->
            @if($lowStock->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-yellow-800 mb-4">📊 Low Stock (21-50%)</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Equipment</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Property No</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Available</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Availability %</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($lowStock as $equipment)
                                <tr class="bg-yellow-50">
                                    <td class="px-4 py-3 font-medium">{{ $equipment->equipment_name }}</td>
                                    <td class="px-4 py-3">{{ $equipment->property_no }}</td>
                                    <td class="px-4 py-3 text-yellow-600 font-semibold">{{ $equipment->available }}</td>
                                    <td class="px-4 py-3">{{ $equipment->quantity }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <div class="w-full bg-gray-200 rounded-full h-2 mr-2 max-w-[100px]">
                                                <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $equipment->availability_percentage }}%"></div>
                                            </div>
                                            <span class="text-sm whitespace-nowrap">{{ round($equipment->availability_percentage, 1) }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">{{ $equipment->location }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- No Alerts Message -->
            @if($outOfStock->count() === 0 && $criticalStock->count() === 0 && $lowStock->count() === 0)
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                    <p class="text-green-800 font-semibold">✅ All equipment stock levels are healthy!</p>
                    <p class="text-green-600 text-sm mt-1">No low stock alerts at this time.</p>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>