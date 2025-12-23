<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use Illuminate\Support\Facades\App;

class SupplyReportController extends Controller
{
    public function SupplyReport()
    {
        $supplies = Supply::with(['reservations', 'unavailable'])
            ->orderBy('item_name', 'asc')
            ->get();

        $totalSupply = 0;
        $totalReserved = 0;
        $totalUnavailable = 0;
        $totalPending = 0;
        $totalApproved = 0;
        $totalRejected = 0;
        $totalCompleted = 0;

        $supplies->transform(function ($supply) use (&$totalSupply, &$totalReserved, &$totalUnavailable, &$totalPending, &$totalApproved, &$totalRejected, &$totalCompleted) {
            $pending = $supply->reservations->where('status', 'Pending');
            $approved = $supply->reservations->where('status', 'Approved');
            $rejected = $supply->reservations->where('status', 'Rejected');
            $completed = $supply->reservations->where('status', 'Completed');

            $supply->pending_count = $pending->count();
            $supply->approved_count = $approved->count();
            $supply->rejected_count = $rejected->count();
            $supply->completed_count = $completed->count();

            $reservedQty = $approved->sum('quantity');
            $unavailableQty = $supply->unavailable->sum('unavailable_quantity');
            $available = $supply->quantity - $reservedQty - $unavailableQty;

            $supply->reserved = $reservedQty;
            $supply->unavailable_qty = $unavailableQty;
            $supply->available = $available;
            $supply->availability_percentage = $supply->quantity > 0
                ? ($available / $supply->quantity) * 100
                : 0;

            $totalSupply += $supply->quantity;
            $totalReserved += $reservedQty;
            $totalUnavailable += $unavailableQty;
            $totalPending += $supply->pending_count;
            $totalApproved += $supply->approved_count;
            $totalRejected += $supply->rejected_count;
            $totalCompleted += $supply->completed_count;

            $supply->borrow_count = $supply->approved_count + $supply->completed_count;

            return $supply;
        });

        $totalAvailable = $totalSupply - $totalReserved - $totalUnavailable;
        $mostBorrowed = $supplies->sortByDesc('borrow_count')->first();
        
        $lowStock = $supplies->filter(fn ($s) => $s->availability_percentage > 26 && $s->availability_percentage <= 50);
        $criticalStock = $supplies->filter(fn ($s) => $s->available > 0 && $s->availability_percentage <= 25);
        $outOfStock = $supplies->filter(fn ($s) => $s->available <= 0);

        $pdf = App::make('dompdf.wrapper');
        
        $pdf->loadView('pdf.report-supply-pdf', [
            'supplies' => $supplies,
            'totalSupply' => $totalSupply,
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

        return $pdf->stream("Supply Report.pdf");
    }
}