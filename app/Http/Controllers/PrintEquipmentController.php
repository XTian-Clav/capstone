<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReserveEquipment;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Filament\Notifications\Notification;

class PrintEquipmentController extends Controller
{
    public function PrintEquipment($id)
    {
        $reserveEquipment = ReserveEquipment::with('equipment')->find($id);

        if ($reserveEquipment)
        {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('pdf.equipment-pdf', compact('reserveEquipment'));
            
            return $pdf->stream();
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
