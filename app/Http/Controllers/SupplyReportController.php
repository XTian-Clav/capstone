<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SupplyReportController extends Controller
{
    public function SupplyReport(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year', now()->year);

        $supplies = Supply::withSum('unavailable as total_unavailable_qty', 'unavailable_quantity')
            ->withSum(['reservations as current_reserved_qty' => fn ($q) => $q->where('status', 'Approved')], 'quantity')
            ->withCount([
                'reservations as pending_count' => fn ($q) => $q->where('status', 'Pending')->whereYear('created_at', $year)->when($month, fn($query) => $query->whereMonth('created_at', $month)),
                'reservations as approved_count' => fn ($q) => $q->where('status', 'Approved')->whereYear('created_at', $year)->when($month, fn($query) => $query->whereMonth('created_at', $month)),
                'reservations as rejected_count' => fn ($q) => $q->where('status', 'Rejected')->whereYear('created_at', $year)->when($month, fn($query) => $query->whereMonth('created_at', $month)),
                'reservations as completed_count' => fn ($q) => $q->where('status', 'Completed')->whereYear('created_at', $year)->when($month, fn($query) => $query->whereMonth('created_at', $month)),
            ])
            ->orderBy('item_name', 'asc')
            ->get();

        $totalSupply = 0; $totalReserved = 0; $totalUnavailable = 0;
        $totalPending = 0; $totalApproved = 0; $totalRejected = 0; $totalCompleted = 0;

        foreach ($supplies as $supply) {
            $supply->borrow_count = $supply->approved_count + $supply->completed_count;
            $supply->available = $supply->quantity - ($supply->current_reserved_qty ?? 0) - ($supply->total_unavailable_qty ?? 0);
            $supply->availability_percentage = $supply->quantity > 0 ? ($supply->available / $supply->quantity) * 100 : 0;

            $totalSupply += $supply->quantity;
            $totalReserved += ($supply->current_reserved_qty ?? 0);
            $totalUnavailable += ($supply->total_unavailable_qty ?? 0);
            $totalPending += $supply->pending_count;
            $totalApproved += $supply->approved_count;
            $totalRejected += $supply->rejected_count;
            $totalCompleted += $supply->completed_count;
        }

        $totalAvailable = $totalSupply - $totalReserved - $totalUnavailable;
        $mostBorrowed = $supplies->sortByDesc('borrow_count')->first();
        
        $lowStock = $supplies->filter(fn ($s) => $s->availability_percentage > 26 && $s->availability_percentage <= 50);
        $criticalStock = $supplies->filter(fn ($s) => $s->available > 0 && $s->availability_percentage <= 25);
        $outOfStock = $supplies->filter(fn ($s) => $s->available <= 0);

        $monthName = $month ? date('F', mktime(0, 0, 0, $month, 1)) : '';
        $reportTitle = $month ? "Supply Report - {$monthName} {$year}" : "Annual Supply Report {$year}";
        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView('pdf.report-supply-pdf', compact(
            'supplies', 'totalSupply', 'totalAvailable', 'totalReserved', 
            'totalUnavailable', 'totalPending', 'totalApproved', 'totalRejected', 
            'totalCompleted', 'mostBorrowed', 'lowStock', 'criticalStock', 
            'outOfStock', 'reportTitle'
        ));

        return $pdf->stream("$reportTitle.pdf");
    }
}