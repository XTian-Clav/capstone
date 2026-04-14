<div style="font-family: sans-serif; overflow: auto;">
    <h2 style="font-size: 12px; font-weight: bold; text-transform: uppercase; margin-bottom: 15px; color: #333; background-color: #fef2f2; padding: 8px 12px; border-left: 4px solid #dc2626;">
        List of Overdue Equipment
    </h2>
    <table style="width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 25px;">
        <thead>
            <tr style="background-color: #fcfcfc;">
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: left; color: #555;">Borrower</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: left; color: #555;">Equipment</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: center; color: #555;">Qty</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: left; color: #555;">Deadline</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: right; color: #555;">Late</th>
            </tr>
        </thead>
        <tbody>
            @forelse($overdueEquipment as $item)
                <tr>
                    <td style="border-bottom: 1px solid #f5f5f5; vertical-align: top; padding: 10px; font-weight: bold;">{{ $item->reserved_by ?? 'N/A' }}</td>
                    <td style="border-bottom: 1px solid #f5f5f5; vertical-align: top; padding: 10px; color: #666;">{{ $item->equipment?->equipment_name ?? 'Deleted Equipment' }}</td>
                    <td style="border-bottom: 1px solid #f5f5f5; vertical-align: top; padding: 10px; text-align: center; font-weight: bold;">{{ $item->quantity }}</td>
                    <td style="border-bottom: 1px solid #f5f5f5; vertical-align: top; padding: 10px; line-height: 1.2;">
                        <span style="display: block;">{{ $item->end_date->format('M d, Y') }}</span>
                        <span style="display: block; color: #777;">{{ $item->end_date->format('h:i A') }}</span>
                    </td>
                    <td style="border-bottom: 1px solid #f5f5f5; vertical-align: top; padding: 10px; color: #dc2626; font-weight: bold; white-space: nowrap;">{{ $item->days_late }} Days</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="padding: 20px; text-align: center; color: #666; border-bottom: 1px solid #f5f5f5;">No equipment is currently overdue.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2 style="font-size: 12px; font-weight: bold; text-transform: uppercase; margin-bottom: 15px; color: #333; background-color: #fef2f2; padding: 8px 12px; border-left: 4px solid #dc2626;">
        List of Overdue Supplies
    </h2>
    <table style="width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 25px;">
        <thead>
            <tr style="background-color: #fcfcfc;">
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: left; color: #555;">Borrower</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: left; color: #555;">Supply</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: center; color: #555;">Qty</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: left; color: #555;">Deadline</th>
                <th style="border-bottom: 1px solid #eee; padding: 12px 11px; text-align: right; color: #555;">Late</th>
            </tr>
        </thead>
        <tbody>
            @forelse($overdueSupply as $item)
                <tr>
                    <td style="border-bottom: 1px solid #f5f5f5; vertical-align: top; padding: 10px; font-weight: bold;">{{ $item->reserved_by ?? 'N/A' }}</td>
                    <td style="border-bottom: 1px solid #f5f5f5; vertical-align: top; padding: 10px; color: #666;">{{ $item->supply?->item_name ?? 'Deleted Supply' }}</td>
                    <td style="border-bottom: 1px solid #f5f5f5; vertical-align: top; padding: 10px; text-align: center; font-weight: bold;">{{ $item->quantity }}</td>
                    <td style="border-bottom: 1px solid #f5f5f5; vertical-align: top; padding: 10px; line-height: 1.2;">
                        <span style="display: block;">{{ $item->end_date->format('M d, Y') }}</span>
                        <span style="display: block; color: #777;">{{ $item->end_date->format('h:i A') }}</span>
                    </td>
                    <td style="border-bottom: 1px solid #f5f5f5; vertical-align: top; padding: 10px; color: #dc2626; font-weight: bold; white-space: nowrap;">{{ $item->days_late }} Days</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="padding: 20px; text-align: center; color: #666; border-bottom: 1px solid #f5f5f5;">No supplies are currently overdue.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>