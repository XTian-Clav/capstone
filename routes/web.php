<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        // User is logged in, redirect to dashboard
        return redirect('/portal');
    }
    return view('landing');
});
