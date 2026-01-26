<x-filament-panels::page>
    <style>
        .text-blue { color: #013267; }
        .text-red { color: #991b1b; }
        .text-green { color: #15803d; }
        .text-orange { color: #c2410c; }
        .text-yellow { color: #ca8a04; }
    </style>

    <div>
        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 20px; color: #374151; padding-left: 10px;">Returned Equipment and Supplies</h2>

        {{-- RETURNED EQUIPMENT --}}
        <div style="background: white; border-radius: 10px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 25px;">
            <div style="background: #f0fdf4; padding: 10px 15px; border-bottom: 1px solid #dcfce7;">
                <h3 class="text-green" style="font-size: 13px; font-weight: bold;">Returned Equipment (Completed)</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 10px 15px; text-align: left; color: #374151;">Borrower</th>
                        <th style="padding: 10px 15px; text-align: left; color: #374151;">Equipment</th>
                        <th style="padding: 10px 15px; text-align: center; width: 10%;">Qty</th>
                        <th style="padding: 10px 15px; text-align: left; width: 25%;">Returned Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returnedEquipment as $item)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 10px 15px; font-weight: bold;">{{ $item->reserved_by }}</td>
                            <td style="padding: 10px 15px;">{{ $item->equipment?->equipment_name }}</td>
                            <td style="padding: 10px 15px; text-align: center; font-weight: bold;">{{ $item->quantity }}</td>
                            <td class="text-green" style="padding: 10px 15px; font-weight: bold;">{{ $item->updated_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="padding: 20px; text-align: center; color: #666;">No items returned yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- RETURNED SUPPLIES --}}
        <div style="background: white; border-radius: 10px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 25px;">
            <div style="background: #f0fdf4; padding: 10px 15px; border-bottom: 1px solid #dcfce7;">
                <h3 class="text-green" style="font-size: 13px; font-weight: bold;">Replaced Supplies (Completed)</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 10px 15px; text-align: left; color: #374151;">Borrower</th>
                        <th style="padding: 10px 15px; text-align: left; color: #374151;">Supply</th>
                        <th style="padding: 10px 15px; text-align: center; width: 10%;">Qty</th>
                        <th style="padding: 10px 15px; text-align: left; width: 25%;">Replacement Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returnedSupply as $item)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 10px 15px; font-weight: bold;">{{ $item->reserved_by }}</td>
                            <td style="padding: 10px 15px;">{{ $item->supply?->item_name }}</td>
                            <td style="padding: 10px 15px; text-align: center; font-weight: bold;">{{ $item->quantity }}</td>
                            <td class="text-green" style="padding: 10px 15px; font-weight: bold;">{{ $item->updated_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="padding: 20px; text-align: center; color: #666;">No supplies replaced yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 20px; color: #374151; padding-left: 10px;">Borrowed Equipment and Supplies</h2>
        
        {{-- BORROWED EQUIPMENT --}}
        <div style="background: white; border-radius: 10px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 25px;">
            <div style="background: #fff7ed; padding: 10px 15px; border-bottom: 1px solid #ffedd5;">
                <h3 class="text-orange" style="font-size: 13px; font-weight: bold;">Currently Borrowed Equipment</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 10px 15px; text-align: left; color: #374151;">Borrower</th>
                        <th style="padding: 10px 15px; text-align: left; color: #374151;">Equipment</th>
                        <th style="padding: 10px 15px; text-align: center; width: 10%;">Qty</th>
                        <th style="padding: 10px 15px; text-align: left; width: 25%;">Expected Return</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowedEquipment as $item)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 10px 15px; font-weight: bold;">{{ $item->reserved_by }}</td>
                            <td style="padding: 10px 15px;">{{ $item->equipment?->equipment_name }}</td>
                            <td style="padding: 10px 15px; text-align: center; font-weight: bold;">{{ $item->quantity }}</td>
                            <td class="text-orange" style="padding: 10px 15px; font-weight: bold;">{{ $item->end_date->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="padding: 20px; text-align: center; color: #666;">No active borrowed items.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- BORROWED SUPPLIES --}}
        <div style="background: white; border-radius: 10px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 25px;">
            <div style="background: #fff7ed; padding: 10px 15px; border-bottom: 1px solid #ffedd5;">
                <h3 class="text-orange" style="font-size: 13px; font-weight: bold;">Currently Borrowed Supplies</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 10px 15px; text-align: left; color: #374151;">Borrower</th>
                        <th style="padding: 10px 15px; text-align: left; color: #374151;">Supply</th>
                        <th style="padding: 10px 15px; text-align: center; width: 10%;">Qty</th>
                        <th style="padding: 10px 15px; text-align: left; width: 25%;">Expected Return</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowedSupply as $item)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 10px 15px; font-weight: bold;">{{ $item->reserved_by }}</td>
                            <td style="padding: 10px 15px;">{{ $item->supply?->item_name }}</td>
                            <td style="padding: 10px 15px; text-align: center; font-weight: bold;">{{ $item->quantity }}</td>
                            <td class="text-orange" style="padding: 10px 15px; font-weight: bold;">{{ $item->end_date->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="padding: 20px; text-align: center; color: #666;">No active borrowed supplies.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 20px; color: #374151; padding-left: 10px;">Overdue Equipment and Supplies</h2>
        
        {{-- OVERDUE EQUIPMENT --}}
        <div style="background: white; border-radius: 10px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 40px;">
            <div style="background: #fef2f2; padding: 10px 15px; border-bottom: 1px solid #fee2e2;">
                <h3 class="text-red" style="font-size: 13px; font-weight: bold;">Overdue Equipment</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 10px 15px; text-align: left; color: #374151;">Borrower</th>
                        <th style="padding: 10px 15px; text-align: left; color: #374151;">Equipment</th>
                        <th style="padding: 10px 15px; text-align: center; width: 10%;">Qty</th>
                        <th style="padding: 10px 15px; text-align: left; width: 15%;">Deadline</th>
                        <th style="padding: 10px 15px; text-align: right; width: 10%;">Late</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($overdueEquipment as $item)
                        @php 
                            $daysLate = $item->end_date->startOfDay()->diffInDays(now()->startOfDay()); 
                        @endphp
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 10px 15px; font-weight: bold;">{{ $item->reserved_by }}</td>
                            <td style="padding: 10px 15px;">{{ $item->equipment?->equipment_name }}</td>
                            <td style="padding: 10px 15px; text-align: center; font-weight: bold;">{{ $item->quantity }}</td>
                            <td style="padding: 10px 15px; font-weight: bold;">{{ $item->end_date->format('M d, Y') }}</td>
                            <td class="text-red" style="padding: 10px 15px; text-align: right; font-weight: bold;">
                                {{ (int) $daysLate }} Days
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="padding: 20px; text-align: center; color: #666;">No overdue items.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- OVERDUE SUPPLIES --}}
        <div style="background: white; border-radius: 10px; border: 1px solid #e5e7eb; overflow: hidden;">
            <div style="background: #fef2f2; padding: 10px 15px; border-bottom: 1px solid #fee2e2;">
                <h3 class="text-red" style="font-size: 13px; font-weight: bold;">Overdue Supplies</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 10px 15px; text-align: left; color: #374151;">Borrower</th>
                        <th style="padding: 10px 15px; text-align: left; color: #374151;">Supply</th>
                        <th style="padding: 10px 15px; text-align: center; width: 10%;">Qty</th>
                        <th style="padding: 10px 15px; text-align: left; width: 15%;">Deadline</th>
                        <th style="padding: 10px 15px; text-align: right; width: 10%;">Late</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($overdueSupply as $item)
                        @php 
                            $daysLate = $item->end_date->startOfDay()->diffInDays(now()->startOfDay()); 
                        @endphp
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 10px 15px; font-weight: bold;">{{ $item->reserved_by }}</td>
                            <td style="padding: 10px 15px;">{{ $item->supply?->item_name }}</td>
                            <td style="padding: 10px 15px; text-align: center; font-weight: bold;">{{ $item->quantity }}</td>
                            <td style="padding: 10px 15px; font-weight: bold;">{{ $item->end_date->format('M d, Y') }}</td>
                            <td class="text-red" style="padding: 10px 15px; text-align: right; font-weight: bold;">
                                {{ (int) $daysLate }} Days
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 20px; text-align: center; color: #666;">No overdue supplies.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>