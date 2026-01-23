<div style="font-family: sans-serif; overflow: auto;">
    <h2 style="font-size: 12px; font-weight: bold; text-transform: uppercase; margin-bottom: 15px; color: #333; background-color: #fff7ed; padding: 8px 12px; border-left: 4px solid #fe800d;">
        List of Returned Equipment
    </h2>
    <table style="width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 25px;">
        <thead>
            <tr style="background-color: #fcfcfc;">
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: left; color: #555;">Borrower</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: left; color: #555;">Equipment</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: center; color: #555;">Qty</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: left; color: #555;">Returned Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($returnedEquipment as $item)
                <tr>
                    <td style="border-bottom: 1px solid #f5f5f5; padding: 10px; font-weight: bold;">{{ $item->reserved_by ?? 'N/A' }}</td>
                    <td style="border-bottom: 1px solid #f5f5f5; padding: 10px; color: #666;">{{ $item->equipment?->equipment_name ?? 'Deleted Equipment' }}</td>
                    <td style="border-bottom: 1px solid #f5f5f5; padding: 10px; text-align: center; font-weight: bold;">{{ $item->quantity }}</td>
                    <td style="border-bottom: 1px solid #f5f5f5; padding: 10px;">{{ $item->updated_at->format('M d, Y h:i A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="padding: 20px; text-align: center; color: #666; border-bottom: 1px solid #f5f5f5;">No items have been returned yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2 style="font-size: 12px; font-weight: bold; text-transform: uppercase; margin-bottom: 15px; color: #333; background-color: #fff7ed; padding: 8px 12px; border-left: 4px solid #fe800d;">
        List of Replaced Supplies
    </h2>
    <table style="width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 25px;">
        <thead>
            <tr style="background-color: #fcfcfc;">
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: left; color: #555;">Borrower</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: left; color: #555;">Supply</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: center; color: #555;">Qty</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: left; color: #555;">Replacement Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($returnedSupply as $item)
                <tr>
                    <td style="border-bottom: 1px solid #f5f5f5; padding: 10px; font-weight: bold;">{{ $item->reserved_by ?? 'N/A' }}</td>
                    <td style="border-bottom: 1px solid #f5f5f5; padding: 10px; color: #666;">{{ $item->supply?->item_name ?? 'Deleted Supply' }}</td>
                    <td style="border-bottom: 1px solid #f5f5f5; padding: 10px; text-align: center; font-weight: bold;">{{ $item->quantity }}</td>
                    <td style="border-bottom: 1px solid #f5f5f5; padding: 10px;">{{ $item->updated_at->format('M d, Y h:i A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="padding: 20px; text-align: center; color: #666; border-bottom: 1px solid #f5f5f5;">No supplies have been replaced yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>