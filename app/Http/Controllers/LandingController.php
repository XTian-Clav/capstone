<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Mentor;
use App\Models\Startup;
use Spatie\Permission\Models\Role;

class LandingController extends Controller
{
    public function index()
    {
        $startupCount = Startup::count();
        $eventCount = Event::count();
        $mentorCount = Mentor::count();

        $investorCount = Role::where('name', 'investor')->exists() 
            ? User::role('investor')->count() 
            : 0;

        $incubateeCount = Role::where('name', 'incubatee')->exists() 
            ? User::role('incubatee')->count() 
            : 0;

        return view('landing', [
            'startupCount' => $startupCount,
            'eventCount' => $eventCount,
            'mentorCount' => $mentorCount,
            'investorCount' => $investorCount,
            'incubateeCount' => $incubateeCount,
        ]);
    }
}