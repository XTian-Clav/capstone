<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        // User is logged in, redirect to dashboard
        return redirect('/portal');
    }
    return view('landing');
});

Route::get('startups', function () {
    return view('startups');
});

Route::get('our-mission', function () {
    return view('our-mission');
});

Route::get('faqs', function () {
    return view('faqs');
});

