<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\EventRule;
use App\Models\Member;
use App\Models\EventScore;
use App\Models\Team;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class EventScoreController extends Controller
{
    public function create(int $event)
    {
        try {
            $event = Event::findOrFail($event)->formatDashboard();
            $teams = Team::where('event_id', $event)->get()->map->format();
            return view('events.score', [
                'event' => $event,
                'teams' => $teams
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('events.index')->with('error', 'Event not found.');
        } catch (\Exception $e) {
            return redirect()->route('events.index')->with('error', $e->getMessage());
        }
    }

    public function store(int $event)
    {
        try {

            $member = Member::where('email', Auth::user()->email)->firstOrFail();
            $member = $member->format();

            $rules = request()->input('rules');

            $score = 0;
            foreach ($rules as $rule) {
                $score += $rule;
            }

            $trophies = [
                'generale' => 0,
                'alcolismo' => 0,
                'pazzia' => 0,
                'professionalita' => 0,
                'resistenza' => 0,
                'seduzione' => 0
            ];

            foreach ($rules as $key => $rule) {
                $eventRule = EventRule::find($key);
                if($eventRule !== null){
                    $eventRule = $eventRule->format();
                    if($eventRule['characteristic'] !== null){
                        $trophies[$eventRule['characteristic']]++;
                    }

                }
            }

            $score = new EventScore([
                'event_id' => $event,
                'member_id' => $member['id'],
                'score' => $score,
                'rules' => $rules,
                'trophies' => $trophies
            ]);

            if ($score->save()) {
                return redirect()->route('events.show', $event)->with('success', 'Punteggio salvato con successo.');
            } else {
                return redirect()->route('score.create', $event)->with('error', 'Problemi nel salvataggio del punteggio. Riprova.');
            }

        } catch (ModelNotFoundException $e) {
            return redirect()->route('events.index')->with('error', 'Event not found.');
        } catch (\Exception $e) {
            return redirect()->route('events.index')->with('error', $e->getMessage());
        }
    }

}
