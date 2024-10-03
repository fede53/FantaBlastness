<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $events = Event::get()->map->formatDashboard();
        return view('dashboard', ['events' => $events]);
    }
}
