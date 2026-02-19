<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class EquipmentReportController extends Controller
{
    public function EquipmentReport(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year', now()->year);

        $equipments = Equipment::withSum('unavailable as total_unavailable_qty', 'unavailable_quantity')
            ->withSum(['reservations as current_reserved_qty' => fn ($q) => $q->where('status', 'Approved')], 'quantity')
            ->withCount([
                'reservations as pending_count' => fn ($q) => $q->where('status', 'Pending')
                    ->whereYear('created_at', $year)
                    ->when($month, fn($query) => $query->whereMonth('created_at', $month)),
                'reservations as approved_count' => fn ($q) => $q->where('status', 'Approved')
                    ->whereYear('created_at', $year)
                    ->when($month, fn($query) => $query->whereMonth('created_at', $month)),
                'reservations as rejected_count' => fn ($q) => $q->where('status', 'Rejected')
                    ->whereYear('created_at', $year)
                    ->when($month, fn($query) => $query->whereMonth('created_at', $month)),
                'reservations as completed_count' => fn ($q) => $q->where('status', 'Completed')
                    ->whereYear('created_at', $year)
                    ->when($month, fn($query) => $query->whereMonth('created_at', $month)),
            ])
            ->orderBy('equipment_name', 'asc')
            ->get();

        $totalEquipment = 0; $totalReserved = 0; $totalUnavailable = 0;
        $totalPending = 0; $totalApproved = 0; $totalRejected = 0; $totalCompleted = 0;

        foreach ($equipments as $equipment) {
            $equipment->borrow_count = $equipment->approved_count + $equipment->completed_count;
            $equipment->available = $equipment->quantity - ($equipment->current_reserved_qty ?? 0) - ($equipment->total_unavailable_qty ?? 0);
            $equipment->availability_percentage = $equipment->quantity > 0 ? ($equipment->available / $equipment->quantity) * 100 : 0;

            $totalEquipment += $equipment->quantity;
            $totalReserved += ($equipment->current_reserved_qty ?? 0);
            $totalUnavailable += ($equipment->total_unavailable_qty ?? 0);
            $totalPending += $equipment->pending_count;
            $totalApproved += $equipment->approved_count;
            $totalRejected += $equipment->rejected_count;
            $totalCompleted += $equipment->completed_count;
        }

        $totalAvailable = $totalEquipment - $totalReserved - $totalUnavailable;
        $mostBorrowed = $equipments->sortByDesc('borrow_count')->first();
        
        $lowStock = $equipments->filter(fn ($e) => $e->availability_percentage > 26 && $e->availability_percentage <= 50);
        $criticalStock = $equipments->filter(fn ($e) => $e->available > 0 && $e->availability_percentage <= 25);
        $outOfStock = $equipments->filter(fn ($e) => $e->available <= 0);

        $monthName = $month ? date('F', mktime(0, 0, 0, $month, 1)) : '';
        $reportTitle = $month ? "Equipment Report - {$monthName} {$year}" : "Annual Equipment Report {$year}";
        $pdf = App::make('dompdf.wrapper');
        
        $pdf->loadView('pdf.report-equipment-pdf', compact(
            'equipments', 'totalEquipment', 'totalAvailable', 'totalReserved', 
            'totalUnavailable', 'totalPending', 'totalApproved', 'totalRejected', 
            'totalCompleted', 'mostBorrowed', 'lowStock', 'criticalStock', 
            'outOfStock', 'reportTitle'
        ));

        return $pdf->stream("{$reportTitle}.pdf");
    }
}