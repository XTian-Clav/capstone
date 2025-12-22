<!-- Borrower Details -->
<div style="font-size: 12px; font-weight: 600; margin-top: 40px; margin-bottom: 10px; text-transform: uppercase; background-color: #fff7ed; padding: 8px 12px; border-left: 4px solid #fe800d;">Borrower Details</div>
    
<table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 12px; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;">
    <tr style="border-bottom: 1px solid #e5e7eb;">
        <th style="background-color: #f9fafb; padding: 10px; width: 35%; text-align: left; color: #4b5563; font-weight: 600;">Company</th>
        <td style="padding: 10px; color: #111827; font-weight: 500;">{{ $reserveSupply->company }}</td>
    </tr>
    <tr style="border-bottom: 1px solid #e5e7eb;">
        <th style="background-color: #f9fafb; padding: 10px; text-align: left; color: #4b5563; font-weight: 600;">Reserved By</th>
        <td style="padding: 10px; color: #111827;">{{ $reserveSupply->reserved_by }}</td>
    </tr>
    <tr style="border-bottom: 1px solid #e5e7eb;">
        <th style="background-color: #f9fafb; padding: 10px; text-align: left; color: #4b5563; font-weight: 600;">Email</th>
        <td style="padding: 10px; color: #111827;">{{ $reserveSupply->email }}</td>
    </tr>
    <tr>
        <th style="background-color: #f9fafb; padding: 10px; text-align: left; color: #4b5563; font-weight: 600;">Contact Number</th>
        <td style="padding: 10px; color: #111827;">{{ $reserveSupply->contact }}</td>
    </tr>
</table>

<!-- Reservation Details -->
<div style="font-size: 12px; font-weight: 600; margin-top: 25px; margin-bottom: 10px; text-transform: uppercase; background-color: #fff7ed; padding: 8px 12px; border-left: 4px solid #fe800d;">Reservation Details</div>

<table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 12px; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;">
    <tr style="border-bottom: 1px solid #e5e7eb;">
        <th style="background-color: #f9fafb; padding: 10px; width: 35%; text-align: left; color: #4b5563; font-weight: 600;">Supply</th>
        <td style="padding: 10px; color: #111827; font-weight: 500;">{{ $reserveSupply->supply->item_name }}</td>
    </tr>
    <tr style="border-bottom: 1px solid #e5e7eb;">
        <th style="background-color: #f9fafb; padding: 10px; text-align: left; color: #4b5563; font-weight: 600;">Quantity</th>
        <td style="padding: 10px; color: #111827;">{{ $reserveSupply->quantity }}</td>
    </tr>
    <tr style="border-bottom: 1px solid #e5e7eb;">
        <th style="background-color: #f9fafb; padding: 10px; text-align: left; color: #4b5563; font-weight: 600;">Start Date</th>
        <td style="padding: 10px; color: #111827;">{{ $reserveSupply->start_date->format('F j, Y - h:i A') }}</td>
    </tr>
    <tr>
        <th style="background-color: #f9fafb; padding: 10px; text-align: left; color: #4b5563; font-weight: 600;">End Date</th>
        <td style="padding: 10px; color: #111827;">{{ $reserveSupply->end_date->format('F j, Y - h:i A') }}</td>
    </tr>
</table>

<!-- Guidelines / T&C -->
<div style="margin-top: 30px; padding: 8px 15px 15px 15px; border: 1px solid #ffedd5; border-radius: 5px; background-color: #fffaf5; font-size: 12px; line-height: 1.6; color: #555;">
    <p style="font-weight: 600; margin: 0 0 6px 0;">Supply Reservation Guidelines:</p>
    <ul style="padding-left: 20px; margin: 0; list-style-type: disc;">
        <li>I agree to replace the exact quantity of the supplies used.</li>
        <li>I assume full responsibility for their proper handling and condition. PITBI/PSU is not obligated to provide replacements for any supplies damaged while in my possession.</li>
        <li>I pledge that the supplies borrowed from PITBI/PSU will be used solely for the official purpose stated above and not for personal purposes.</li>
    </ul>
</div>

<!-- Signature -->
<table style="width: 100%; margin-top: 30px; font-size: 12px; border-collapse: collapse;">
    <tr>
        <td style="width: 50%; vertical-align: top;">
            <p style="margin: 0 0 8px 0;">Borrower’s Name and Signature:</p>
        </td>
        <td style="width: 50%; vertical-align: top;">
            <p style="margin: 0 0 8px 0;">Date Printed:</p>
        </td>
    </tr>

    <tr>
        <td style="width: 50%; vertical-align: top;">
            <div style="margin-bottom: 10px;">
                <p style="margin: 0 0 2px 0; font-size: 13px;">{{ $reserveSupply->reserved_by }}</p>
                <div style="width: 60%; border-top: 1px solid #000;"></div>
            </div>
        </td>
        <td style="width: 50%; vertical-align: top;">
            <div style="margin-bottom: 10px;">
                <p style="margin: 0 0 2px 0; font-size: 13px;">{{ now()->format('F j, Y - h:i A') }}</p>
                <div style="width: 60%; border-top: 1px solid #000;"></div>
            </div>
        </td>
    </tr>
</table> 

<!-- Released By -->
<div style="font-size: 12px; margin-top: 20px; text-transform: uppercase;">Released By:</div>
<table style="width: 100%; margin-top: 10px;">
    <tr>
        <td style="width: 50%; vertical-align: top;">
            <p style="font-weight: 600; margin: 0 0 2px 0;">GLEN D. CERALES</p>
            <p style="font-size: 12px; margin: 0;">Facility and Administrative Lead, PITBI</p>
        </td>

        <td style="width: 50%; vertical-align: top;">
            <p style="font-weight: 600; margin: 0 0 2px 0;">MITZI ALMIRA V. GARCIA</p>
            <p style="font-size: 12px; margin: 0;">TBI Director, PITBI</p>
        </td>
    </tr>
</table>