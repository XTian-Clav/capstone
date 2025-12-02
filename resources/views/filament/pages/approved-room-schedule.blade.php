@php
    /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\ReserveRoom> $approvedReservations */
    // Extract unique, lowercased room types for the select dropdown options
    $uniqueRoomTypes = $approvedReservations->pluck('room.room_type')
        ->filter() // Remove any null room types
        ->unique()
        ->sort()
        ->all();
@endphp

<div class="space-y-4 p-2 sm:p-4" x-data="{ selectedRoomType: '' }">
    <h3 class="sr-only">Approved Room Schedule Details</h3>
    
    {{-- Room Type Filter Select (Replaces Search Bar) --}}
    <select 
        x-model="selectedRoomType"
        class="fi-select-input block w-full rounded-lg border-gray-300 shadow-sm transition duration-75 focus:ring-primary-600 focus:border-primary-600 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
    >
        <option value="">All Room Types</option>
        {{-- Populate options from the unique room types in the collection --}}
        @foreach ($uniqueRoomTypes as $roomType)
            <option value="{{ strtolower($roomType) }}">{{ $roomType }}</option>
        @endforeach
    </select>

    @if ($approvedReservations->isEmpty())
        <div class="flex items-center justify-center p-8 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <p class="text-lg text-gray-500 dark:text-gray-400">No upcoming approved reservations found.</p>
        </div>
    @else
        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider w-1/4">Room & Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider w-1/4">Booked By</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider w-1/4">Start Time</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider w-1/4">End Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($approvedReservations as $reservation)
                        @php
                            // Use safe accessors to prevent PHP crashes if relations are null
                            $roomType = strtolower($reservation->room->room_type ?? '');
                            $roomLocation = strtolower($reservation->room->location ?? '');
                            $bookerName = strtolower($reservation->user->name ?? ''); 
                        @endphp
                        
                        <tr 
                            x-show="
                                selectedRoomType === '' || 
                                '{{ $roomType }}' === selectedRoomType
                            "
                            class="{{ $loop->even ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }}"
                        >
                            {{-- Room & Type --}}
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $reservation->room->room_type ?? 'N/A Room' }}
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $reservation->room->location ?? 'Unknown Location' }}
                                </div>
                            </td>
                            
                            {{-- Booked By --}}
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                {{ $reservation->user->name ?? 'User Not Found' }}
                            </td>
                            
                            {{-- Start Time --}}
                            <td class="px-4 py-3 text-sm text-green-600 dark:text-green-400">
                                {{ $reservation->start_date->format('M d, Y') }}<br>
                                <span class="font-bold">{{ $reservation->start_date->format('H:i A') }}</span>
                            </td>
                            
                            {{-- End Time --}}
                            <td class="px-4 py-3 text-sm text-red-600 dark:text-red-400">
                                {{ $reservation->end_date->format('M d, Y') }}<br>
                                <span class="font-bold">{{ $reservation->end_date->format('H:i A') }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>