<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReserveSupply;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Filament\Notifications\Notification;

class PrintSupplyController extends Controller
{
    public function PrintSupply($id)
    {
        $reserveSupply = ReserveSupply::with('supply')->find($id);

        if ($reserveSupply)
        {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('pdf.supply-pdf', compact('reserveSupply'));
            
            $reservedBy = $reserveSupply->reserved_by;
            $date = date('m-d-Y');
            $filename = "Supply Reservation - {$reservedBy} - {$date}.pdf";
            
            return $pdf->stream($filename);
        }
        else
        {
            Notification::make()
                ->title('No Record Found.')
                ->danger()
                ->send();
            
            return redirect()->back();
        }
    }
}
