<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ReserveSupply;
use App\Models\ReserveEquipment;
use Illuminate\Support\Facades\App;

class OverdueItemsController extends Controller
{
    public function OverdueItems(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year', now()->year);
        $now = Carbon::now();

        $overdueEquipment = ReserveEquipment::with('equipment')
            ->where('status', 'Approved')
            ->where('end_date', '<', $now)
            ->get()
            ->map(function ($item) use ($now) {
                $item->days_late = (int) Carbon::parse($item->end_date)
                    ->startOfDay()
                    ->diffInDays($now->startOfDay());
                return $item;
            })
            ->sortBy('end_date');

        $overdueSupply = ReserveSupply::with('supply')
            ->where('status', 'Approved')
            ->where('end_date', '<', $now)
            ->get()
            ->map(function ($item) use ($now) {
                $item->days_late = (int) Carbon::parse($item->end_date)
                    ->startOfDay()
                    ->diffInDays($now->startOfDay());
                return $item;
            })
            ->sortBy('end_date');

        $monthName = $month ? date('F', mktime(0, 0, 0, $month, 1)) : '';
        $reportTitle = $month ? "Overdue Items Report - {$monthName} {$year}" : "Annual Overdue Items Report {$year}";
        
        $pdf = App::make('dompdf.wrapper');
        
        $pdf->loadView('pdf.overdue-items-pdf', [
            'overdueEquipment' => $overdueEquipment,
            'overdueSupply' => $overdueSupply,
            'reportTitle' => $reportTitle,
            'date' => now()->format('m/d/Y'),
        ]);

        return $pdf->stream("$reportTitle.pdf");
    }
}