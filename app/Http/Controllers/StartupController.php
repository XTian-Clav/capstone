<?php

namespace App\Http\Controllers;

use App\Models\Startup;
use Illuminate\Http\Request;

class StartupController extends Controller
{
    public function index()
    {
        $startups = Startup::where('status', 'Approved')->paginate(8);
        return view('startups', compact('startups'));
    }
}
