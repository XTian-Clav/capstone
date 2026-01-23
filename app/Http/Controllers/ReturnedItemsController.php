<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReserveSupply;
use App\Models\ReserveEquipment;
use Illuminate\Support\Facades\App;

class ReturnedItemsController extends Controller
{
    public function ReturnedItems()
    {
        $returnedEquipment = ReserveEquipment::with('equipment')
            ->where('status', 'Completed')
            ->latest('updated_at')
            ->get();

        $returnedSupply = ReserveSupply::with('supply')
            ->where('status', 'Completed')
            ->latest('updated_at')
            ->get();

        $pdf = App::make('dompdf.wrapper');
        
        $pdf->loadView('pdf.returned-items-pdf', [
            'returnedEquipment' => $returnedEquipment,
            'returnedSupply' => $returnedSupply,
            'date' => now()->format('m/d/Y'),
        ]);

        return $pdf->stream("Returned Items Report.pdf");
    }
}
