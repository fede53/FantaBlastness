<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventScore;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $events = Event::get()->map->formatDashboard();
        return view('dashboard', ['events' => $events]);
    }
}
