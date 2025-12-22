<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class RoomReportController extends Controller
{
    public function RoomReport()
    {
        $rooms = Room::with('reservations')->get();
        
        $totalRooms = 0;
        $totalRevenue = 0;
        $totalLostRevenue = 0;

        $rooms->transform(function ($room) use (&$totalRooms, &$totalRevenue, &$totalLostRevenue) {
            $all = $room->reservations;
            
            $room->approved_count = $all->where('status', 'Approved')->count();
            $room->rejected_count = $all->where('status', 'Rejected')->count();
            $room->total_requests = $all->count();
            
            $room->revenue = $room->approved_count * $room->room_rate;
            $room->lost_revenue = $room->rejected_count * $room->room_rate;

            $totalRooms++;
            $totalRevenue += $room->revenue;
            $totalLostRevenue += $room->lost_revenue;

            return $room;
        });

        $pdf = App::make('dompdf.wrapper');
        
        $pdf->loadView('pdf.report-room-pdf', [
            'rooms' => $rooms,
            'totalRooms' => $totalRooms,
            'totalRevenue' => $totalRevenue,
            'totalLostRevenue' => $totalLostRevenue,
        ]);

        return $pdf->stream("Room Report.pdf");
    }
}
