<?php

namespace App\Http\Controllers;

use App\Models\ReserveRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Filament\Notifications\Notification;

class PrintRoomController extends Controller
{
    public function PrintRoom($id)
    {
        $reserveRoom = ReserveRoom::with('room')->find($id);

        if ($reserveRoom)
        {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('pdf.room-pdf', compact('reserveRoom'));
            
            $reservedBy = $reserveRoom->reserved_by;
            $date = date('m-d-Y');
            $filename = "Room Reservation - {$reservedBy} - {$date}.pdf";
            
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
