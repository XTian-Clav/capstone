<x-filament-panels::page>
<div style="width: 210mm; min-height: 297mm; padding: 25mm 20mm; margin: auto; background: white; font-family: font-family: 'Times New Roman', Times, serif;">
<!-- Header -->
<table style="width: 100%; margin-bottom: 25px; text-align: center;">
    <tr>
        <!-- Left Logo -->
        <td style="width: 20%; text-align: left; vertical-align: top;">
            <img src="{{ asset('assets/logo/pdf/pdf-psu-logo.jpg') }}"
                    alt="PSU Logo"
                    style="width: 90px; height: auto;">
        </td>

        <!-- Center Text -->
        <td style="width: 60%; text-align: center; vertical-align: top;">
            <div style="font-size: 14px; margin-bottom: 2px;">Republic of the Philippines</div>
            <div style="font-size: 14px; font-weight: 700; margin-bottom: 2px; text-transform: uppercase;">
                Palawan State University
            </div>
            <div style="font-size: 14px; margin-bottom: 20px;">Puerto Princesa City</div>
            <div style="font-size: 14px; font-weight: 700; margin-bottom: 2px;">Supply Reservation</div>
            <div style="font-size: 14px;">Palawan International Technology Business Incubator</div>
        </td>

        <!-- Right Logo -->
        <td style="width: 20%; text-align: right; vertical-align: top;">
            <img src="{{ asset('assets/logo/pdf/pdf-pitbi-logo.jpg') }}" 
                    alt="PITBI Logo" 
                    style="width: 100px; height: auto;">
        </td>
    </tr>
</table>
@include('pdf-template.pdf-template-supply')
</x-filament-panels::page>
