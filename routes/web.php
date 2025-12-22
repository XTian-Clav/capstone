<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\StartupController;
use App\Http\Controllers\PrintRoomController;
use App\Http\Controllers\RoomReportController;
use App\Http\Controllers\PrintSupplyController;
use App\Http\Controllers\PrintEquipmentController;
use App\Http\Controllers\PrintGuidelineController;

//Landing Pages
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/portal');
    }
    
    return (new LandingController())->index(); 
});

Route::get('/startups', [StartupController::class, 'index']);

Route::get('our-mission', function () {
    return view('our-mission');
});

Route::get('faqs', function () {
    return view('faqs');
});

Route::get('contact', function () {
    return view('contact');
});

//Print Function
Route::get('/portal/print-guidelines', [PrintGuidelineController::class, 'PrintGuidelines'])->name('PrintGuidelines');

Route::get('/portal/print-room/{id}', [PrintRoomController::class, 'PrintRoom'])->name('PrintRoom');

Route::get('/portal/print-supply/{id}', [PrintSupplyController::class, 'PrintSupply'])->name('PrintSupply');

Route::get('/portal/print-equipment/{id}', [PrintEquipmentController::class, 'PrintEquipment'])->name('PrintEquipment');

//Print Reports
Route::get('/portal/print-room-report', [RoomReportController::class, 'RoomReport'])->name('RoomReport');