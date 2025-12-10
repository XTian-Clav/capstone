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
