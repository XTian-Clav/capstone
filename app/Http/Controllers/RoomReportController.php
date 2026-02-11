<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\ReserveRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class RoomReportController extends Controller
{
    public function RoomReport(Request $request)
    {
        $month = $request->query('month'); 
        $year = $request->query('year', now()->year);

        $rooms = Room::withCount([
            'reservations as approved_count' => function ($query) use ($month, $year) {
                $query->where('status', 'Approved')->whereYear('created_at', $year);
                if ($month) $query->whereMonth('created_at', $month);
            },
            'reservations as rejected_count' => function ($query) use ($month, $year) {
                $query->where('status', 'Rejected')->whereYear('created_at', $year);
                if ($month) $query->whereMonth('created_at', $month);
            },
            'reservations as total_requests' => function ($query) use ($month, $year) {
                $query->whereYear('created_at', $year);
                if ($month) $query->whereMonth('created_at', $month);
            }
        ])->get();

        $totalRooms = $rooms->count();
        $totalRevenue = 0;
        $totalLostRevenue = 0;

        foreach ($rooms as $room) {
            $room->revenue = $room->approved_count * $room->room_rate;
            $room->lost_revenue = $room->rejected_count * $room->room_rate;
            
            $totalRevenue += $room->revenue;
            $totalLostRevenue += $room->lost_revenue;
        }

        $reserveRoomQuery = ReserveRoom::with('room')->whereYear('created_at', $year);
        if ($month) $reserveRoomQuery->whereMonth('created_at', $month);
        
        $reserveRoom = $reserveRoomQuery->latest()->first();

        $monthName = $month ? date('F', mktime(0, 0, 0, $month, 1)) : '';

        $reportTitle = $month 
            ? "Room Report - {$monthName} {$year}" 
            : "Annual Room Report {$year}";

        $pdf = App::make('dompdf.wrapper');
        return $pdf->loadView('pdf.report-room-pdf', compact(
            'rooms', 
            'totalRooms', 
            'totalRevenue', 
            'totalLostRevenue', 
            'reserveRoom', 
            'reportTitle'
        ))->stream("$reportTitle.pdf");
    }
}