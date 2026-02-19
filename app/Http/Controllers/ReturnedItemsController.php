<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReserveSupply;
use App\Models\ReserveEquipment;
use Illuminate\Support\Facades\App;

class ReturnedItemsController extends Controller
{
    public function ReturnedItems(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year', now()->year);

        $returnedEquipment = ReserveEquipment::with('equipment')
            ->where('status', 'Completed')
            ->whereYear('updated_at', $year)
            ->when($month, fn($query) => $query->whereMonth('updated_at', $month))
            ->latest('updated_at')
            ->get();

        $returnedSupply = ReserveSupply::with('supply')
            ->where('status', 'Completed')
            ->whereYear('updated_at', $year)
            ->when($month, fn($query) => $query->whereMonth('updated_at', $month))
            ->latest('updated_at')
            ->get();

        $monthName = $month ? date('F', mktime(0, 0, 0, $month, 1)) : '';
        $reportTitle = $month ? "Returned Items Report - {$monthName} {$year}" : "Annual Returned Items Report {$year}";
        $pdf = App::make('dompdf.wrapper');
        
        $pdf->loadView('pdf.returned-items-pdf', [
            'returnedEquipment' => $returnedEquipment,
            'returnedSupply' => $returnedSupply,
            'reportTitle' => $reportTitle,
            'date' => now()->format('m/d/Y'),
        ]);

        return $pdf->stream("$reportTitle.pdf");
    }
}