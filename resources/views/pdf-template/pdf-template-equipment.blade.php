<head>
    <title>Equipment Reservation - {{ $reserveEquipment->reserved_by }} - {{ now()->format('M-d-Y') }}</title>
</head>

<div style="background: white; font-family: font-family: 'Times New Roman', Times, serif;">
    <!-- Header -->
    <div style="position: relative; text-align: center; margin-bottom: 25px; width: 100%;">
        <!-- Left Logo -->
        <img src="{{ asset('assets/logo/psu-logo.png') }}" alt="PSU Logo" style="position: absolute; left: 0; width: 90px; height: auto;">
    
        <!-- Right Logo -->
        <img src="{{ asset('assets/logo/logo-with-text.png') }}" alt="PITBI Logo" style="position: absolute; right: 0; width: 100px; height: auto;">
    
        <!-- Centered Text -->
        <div style="font-size: 14px; margin-bottom: 2px;">Republic of the Philippines</div>
        <div style="font-size: 14px; font-weight: 700; margin-bottom: 2px; text-transform: uppercase;">Palawan State University</div>
        <div style="font-size: 14px; margin-bottom: 20px;">Puerto Princesa City</div>
        <div style="font-size: 14px; font-weight: 700; margin-bottom: 2px;">Equipment Reservation</div>
        <div style="font-size: 14px;">Palawan International Technology Business Incubator</div>
    </div>        

    <!-- Reservation Details -->
    <div style="font-size: 14px; font-weight: 600; margin-top: 25px; margin-bottom: 10px; text-transform: uppercase">Reservation Details</div>
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 12px;">
        <tr>
            <th style="border: 1px solid #aaa; padding: 10px; text-align: left; width: 35%;">Company</th>
            <td style="border: 1px solid #aaa; padding: 10px;">{{ $reserveEquipment->company }}</td>
        </tr>
        <tr>
            <th style="border: 1px solid #aaa; padding: 10px; text-align: left;">Reserved By</th>
            <td style="border: 1px solid #aaa; padding: 10px;">{{ $reserveEquipment->reserved_by }}</td>
        </tr>
        <tr>
            <th style="border: 1px solid #aaa; padding: 10px; text-align: left;">Email</th>
            <td style="border: 1px solid #aaa; padding: 10px;">{{ $reserveEquipment->email }}</td>
        </tr>
        <tr>
            <th style="border: 1px solid #aaa; padding: 10px; text-align: left;">Contact Number</th>
            <td style="border: 1px solid #aaa; padding: 10px;">{{ $reserveEquipment->contact }}</td>
        </tr>
        <tr>
            <th style="border: 1px solid #aaa; padding: 10px; text-align: left;">Equipment</th>
            <td style="border: 1px solid #aaa; padding: 10px;">{{ $reserveEquipment->equipment->equipment_name }}</td>
        </tr>
        <tr>
            <th style="border: 1px solid #aaa; padding: 10px; text-align: left;">Quantity</th>
            <td style="border: 1px solid #aaa; padding: 10px;">{{ $reserveEquipment->quantity }}</td>
        </tr>
        <tr>
            <th style="border: 1px solid #aaa; padding: 10px; text-align: left;">Start Date</th>
            <td style="border: 1px solid #aaa; padding: 10px;">{{ $reserveEquipment->start_date }}</td>
        </tr>
        <tr>
            <th style="border: 1px solid #aaa; padding: 10px; text-align: left;">End Date</th>
            <td style="border: 1px solid #aaa; padding: 10px;">{{ $reserveEquipment->end_date }}</td>
        </tr>
    </table>

    <!-- Guidelines / T&C -->
    <div style="margin-top: 30px; padding: 15px; border: 1px solid #ccc; border-radius: 5px; background-color: #f9f9f9; font-size: 12px; line-height: 1.6; color: #555;">
        <p style="font-weight: 600; margin-bottom: 8px;">Equipment Reservation Guidelines:</p>
        <ul style="padding-left: 20px; margin: 0; list-style-type: disc;">
            <li>I agree to promptly return the equipment borrowed.</li>
            <li>I agree to pay for any damage or loss of the equipment during the time when the equipment is in my possession.</li>
            <li>I pledge that the equipment I borrowed from PITBI/PSU will be used solely for the official purpose stated above and not for personal purposes.</li>
        </ul>
    </div>

    <!-- Signature -->
    <div style="margin-top: 20px;">
        <div style="max-width: 200px; font-size: 12px;">
            <p>Borrower’s Name and Signature:</p>
            <div style="margin-top: 20px; width: 100%; border-top: 1px solid #000;"></div>
        </div>
    </div>

    <!-- Released By -->
    <div style="font-size: 14px; margin-top: 30px; text-transform: uppercase;">Released By:</div>
    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
        <div style="flex: 1; margin-right: 20px;">
            <p style="font-weight: 600;">GLEN D. CERALES</p>
            <p style="font-size: 13px;">Facility and Administrative Lead, PITBI</p>
        </div>
        <div style="flex: 1; margin-left: 20px;">
            <p style="font-weight: 600;">MITZI ALMIRA V. GARCIA</p>
            <p style="font-size: 13px;">TBI Director, PITBI</p>
        </div>
    </div>

    <!-- Footer -->
    <div style="text-align: right; font-size: 12px; opacity: 0.7; margin-top: 40px;">
        Generated on {{ now()->format('M-d-Y') }}
    </div>
</div>