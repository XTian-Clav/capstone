<x-filament-panels::page>
    <style>
        .text-blue { color: #013267; }
        .text-red { color: #991b1b; }
        .text-green { color: #15803d; }
        .text-orange { color: #c2410c; }
        .text-yellow { color: #ca8a04; }
    </style>

    <div>
        <h3 style="font-size: 16px; font-weight: bold; margin-bottom: 15px;">List of Returned Equipment</h3>
        <div style="background: white; border-radius: 10px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 25px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 12px 15px; text-align: left; color: #374151;">Borrower</th>
                        <th style="padding: 12px 15px; text-align: left; color: #374151;">Equipment</th>
                        <th style="padding: 12px 15px; text-align: center; color: #374151;">Qty</th>
                        <th style="padding: 12px 15px; text-align: left; color: #374151;">Returned Date</th>
                    </tr>
                </thead>
                <tbody style="color: #374151;">
                    @forelse($returnedEquipment as $item)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 12px 15px; font-weight: bold;">{{ $item->reserved_by ?? 'N/A' }}</td>
                            <td style="padding: 12px 15px;">{{ $item->equipment?->equipment_name ?? 'Deleted Equipment' }}</td>
                            <td style="padding: 12px 15px; text-align: center; font-weight: bold;">{{ $item->quantity }}</td>
                            <td style="padding: 12px 15px;">{{ $item->updated_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 20px; text-align: center; color: #666;">No items have been returned yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h3 style="font-size: 16px; font-weight: bold; margin-bottom: 15px;">List of Replaced Supplies</h3>
        <div style="background: white; border-radius: 10px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 25px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 12px 15px; text-align: left; color: #374151;">Borrower</th>
                        <th style="padding: 12px 15px; text-align: left; color: #374151;">Supply</th>
                        <th style="padding: 12px 15px; text-align: center; color: #374151;">Qty</th>
                        <th style="padding: 12px 15px; text-align: left; color: #374151;">Replacement Date</th>
                    </tr>
                </thead>
                <tbody style="color: #374151;">
                    @forelse($returnedSupply as $item)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 12px 15px; font-weight: bold;">{{ $item->reserved_by ?? 'N/A' }}</td>
                            <td style="padding: 12px 15px;">{{ $item->supply?->item_name ?? 'Deleted Equipment' }}</td>
                            <td style="padding: 12px 15px; text-align: center; font-weight: bold;">{{ $item->quantity }}</td>
                            <td style="padding: 12px 15px;">{{ $item->updated_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 20px; text-align: center; color: #666;">No items have been returned yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
