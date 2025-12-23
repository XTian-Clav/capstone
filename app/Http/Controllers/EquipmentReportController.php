<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Support\Facades\App;

class EquipmentReportController extends Controller
{
    public function EquipmentReport()
    {
        $equipments = Equipment::with(['reservations', 'unavailable'])
            ->orderBy('equipment_name', 'asc')
            ->get();

        $totalEquipment = 0;
        $totalAvailable = 0;
        $totalReserved = 0;
        $totalUnavailable = 0;
        $totalPending = 0;
        $totalApproved = 0;
        $totalRejected = 0;
        $totalCompleted = 0;

        $equipments->transform(function ($equipment) use (&$totalEquipment, &$totalReserved, &$totalUnavailable, &$totalPending, &$totalApproved, &$totalRejected, &$totalCompleted) {
            $pending = $equipment->reservations->where('status', 'Pending');
            $approved = $equipment->reservations->where('status', 'Approved');
            $rejected = $equipment->reservations->where('status', 'Rejected');
            $completed = $equipment->reservations->where('status', 'Completed');

            $equipment->pending_count = $pending->count();
            $equipment->approved_count = $approved->count();
            $equipment->rejected_count = $rejected->count();
            $equipment->completed_count = $completed->count();

            $reservedQty = $approved->sum('quantity');
            $unavailableQty = $equipment->unavailable->sum('unavailable_quantity');
            $available = $equipment->quantity - $reservedQty - $unavailableQty;

            $equipment->available = $available;
            $equipment->availability_percentage = $equipment->quantity > 0
                ? ($available / $equipment->quantity) * 100
                : 0;

            $totalEquipment += $equipment->quantity;
            $totalReserved += $reservedQty;
            $totalUnavailable += $unavailableQty;
            $totalPending += $equipment->pending_count;
            $totalApproved += $equipment->approved_count;
            $totalRejected += $equipment->rejected_count;
            $totalCompleted += $equipment->completed_count;

            $equipment->borrow_count = $equipment->approved_count + $equipment->completed_count;

            return $equipment;
        });

        $totalAvailable = $totalEquipment - $totalReserved - $totalUnavailable;
        $mostBorrowed = $equipments->sortByDesc('borrow_count')->first();

        $lowStock = $equipments->filter(fn ($e) => 
            $e->availability_percentage > 26 && $e->availability_percentage <= 50
        );

        $criticalStock = $equipments->filter(fn ($e) => 
            $e->available > 0 && $e->availability_percentage <= 25
        );

        $outOfStock = $equipments->filter(fn ($e) => 
            $e->available <= 0
        );

        $pdf = App::make('dompdf.wrapper');
        
        $pdf->loadView('pdf.report-equipment-pdf', [
            'equipments' => $equipments,
            'totalEquipment' => $totalEquipment,
            'totalAvailable' => $totalAvailable,
            'totalReserved' => $totalReserved,
            'totalUnavailable' => $totalUnavailable,
            'totalPending' => $totalPending,
            'totalApproved' => $totalApproved,
            'totalRejected' => $totalRejected,
            'totalCompleted' => $totalCompleted,
            'mostBorrowed' => $mostBorrowed,
            'lowStock' => $lowStock,
            'criticalStock' => $criticalStock,
            'outOfStock' => $outOfStock,
        ]);

        return $pdf->stream("Equipment Report.pdf");
    }
}