<!-- Reservation Details -->
<div style="font-size: 12px; font-weight: 600; margin-top: 40px; margin-bottom: 10px; text-transform: uppercase">Borrower Details</div>
    
<!-- Borrower Details -->
<table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 12px;">
    <tr>
        <th style="border: 1px solid #aaa; padding: 10px; width: 35%; text-align: left;">Company</th>
        <td style="border: 1px solid #aaa; padding: 10px;">{{ $reserveRoom->company }}</td>
    </tr>
    <tr>
        <th style="border: 1px solid #aaa; padding: 10px; text-align: left;">Reserved By</th>
        <td style="border: 1px solid #aaa; padding: 10px;">{{ $reserveRoom->reserved_by }}</td>
    </tr>
    <tr>
        <th style="border: 1px solid #aaa; padding: 10px; text-align: left;">Email</th>
        <td style="border: 1px solid #aaa; padding: 10px;">{{ $reserveRoom->email }}</td>
    </tr>
    <tr>
        <th style="border: 1px solid #aaa; padding: 10px; text-align: left;">Contact Number</th>
        <td style="border: 1px solid #aaa; padding: 10px;">{{ $reserveRoom->contact }}</td>
    </tr>
</table>

<!-- Reservation Details -->
<div style="font-size: 12px; font-weight: 600; margin-top: 25px; margin-bottom: 10px; text-transform: uppercase">Reservation Details</div>

<table style="width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 12px;">
    <tr>
        <th style="border: 1px solid #aaa; padding: 10px; width: 35%; text-align: left;">Room</th>
        <td style="border: 1px solid #aaa; padding: 10px;">{{ $reserveRoom->room->room_type }}</td>
    </tr>
    <tr>
        <th style="border: 1px solid #aaa; padding: 10px; text-align: left;">Location</th>
        <td style="border: 1px solid #aaa; padding: 10px;">{{ $reserveRoom->room->location }}</td>
    </tr>
    <tr>
        <th style="border: 1px solid #aaa; padding: 10px; text-align: left;">Inclusions</th>
        <td style="border: 1px solid #aaa; padding: 10px;">{{ $reserveRoom->room->inclusions }}</td>
    </tr>
    <tr>
        <th style="border: 1px solid #aaa; padding: 10px; text-align: left;">Start Date</th>
        <td style="border: 1px solid #aaa; padding: 10px;">{{ $reserveRoom->start_date->format('F j, Y - h:i A') }}</td>
    </tr>
    <tr>
        <th style="border: 1px solid #aaa; padding: 10px; text-align: left;">End Date</th>
        <td style="border: 1px solid #aaa; padding: 10px;">{{ $reserveRoom->end_date->format('F j, Y - h:i A') }}</td>
    </tr>
</table>

<!-- Guidelines / T&C -->
<div style="margin-top: 30px; padding: 8px 15px 15px 15px; border: 1px solid #ccc; border-radius: 5px; background-color: #f9f9f9; font-size: 12px; line-height: 1.6; color: #555;">
    <p style="font-weight: 600; margin: 0 0 6px 0;">Room Reservation Guidelines:</p>
    <ul style="padding-left: 20px; margin: 0; list-style-type: disc;">
        <li>Follow PITBI’s operating hours.</li>
        <li>Turn off all electronics and lights before leaving.</li>
        <li>Keep the space clean and dispose of trash properly.</li>
        <li>Respect shared spaces and other occupants.</li>
        <li>Securely lock doors and report any security concerns.</li>
        <li>Complete a Client Satisfaction Measurement after the event.</li>
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
                <p style="margin: 0 0 2px 0; font-size: 13px;">{{ $reserveRoom->reserved_by }}</p>
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