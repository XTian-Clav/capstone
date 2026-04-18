<x-filament-panels::page>
    <style>
        .attendee-table { width: 100%; border-collapse: separate; border-spacing: 0; background: white; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; }
        .attendee-table th { background: #f9fafb; padding: 12px 24px; text-align: left; font-size: 12px; font-weight: 800; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
        .attendee-table td { padding: 16px 24px; border-bottom: 1px solid #f3f4f6; color: #111827; }
        .table-container { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; }
    </style>

    {{-- Search Bar --}}
    <div class="mb-6">
        {{ $this->form }}
    </div>

    {{-- Table --}}
    <div class="table-container">
        <table class="attendee-table">
            <thead>
                <tr>
                    <th>Attendee</th>
                    <th>Email</th>
                    <th>Registered</th>
                    <th style="text-align: center;">Attendance</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($this->attendees as $index => $attendee)
                    <tr wire:key="attendee-{{ $attendee['user_id'] }}">
                        <td><strong style="font-size: 14px;">{{ $attendee['name'] }}</strong></td>
                        <td><span style="font-size: 13px; color: #6b7280;">{{ $attendee['email'] ?? 'N/A' }}</span></td>
                        <td><div style="font-size: 13px; color: #6b7280;">{{ $attendee['registered_at'] }}</div></td>
                        <td style="text-align: center;">
                            {{ ($this->updateStatusAction)(['index' => $index]) }}
                        </td>
                        <td style="text-align: right;">
                            {{ ($this->deleteAttendeeAction)(['index' => $index]) }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="padding: 60px; text-align: center; color: #9ca3af;">No records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-filament-actions::modals />
</x-filament-panels::page>