<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Team;

class UserHaveATeam
{
    public function handle(Request $request, Closure $next)
    {
        $userId = Auth::id();
        $eventId = $request->route('event');
        $hasTeam = Team::where('user_id', $userId)->where('event_id', $eventId)->exists();
        if ($hasTeam) {
            return redirect()->route('events.show', ['event' => $eventId])->with('message', 'Hai già una squadra per questo evento.');
        }
        return $next($request);
    }
}
