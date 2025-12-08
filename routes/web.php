<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\StartupController;

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

