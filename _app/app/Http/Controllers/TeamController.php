<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Member; // Assumendo che ci sia un modello per i membri
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'team' => 'required|array',
            'name' => 'required|string',
            'event_id' => 'required|exists:events,id',
        ]);

        // Crea la squadra
        $team = Team::create([
            'user_id' => Auth::id(),
            'event_id' => $request->event_id,
            'name' => $request->name,
        ]);

        // Salva i membri e calcola le caratteristiche aggregate
        $costs = $request->input("cost");
        $totalCharacteristics = [
            'pazzia' => 0,
            'alcolismo' => 0,
            'resistenza' => 0,
            'socialita' => 0,
            'seduzione' => 0,
            'professionalita' => 0
        ];

        $numMembers = count($request->team);

        foreach ($request->team as $memberId => $value) {
            $member = Member::findOrFail($memberId);
            $characteristics = json_decode($member->characteristics, true);

            foreach ($totalCharacteristics as $key => &$total) {
                $total += $characteristics[$key];
            }

            TeamMember::create([
                'team_id' => $team->id,
                'member_id' => $memberId,
                'captain' => ($request->captain == $memberId) ? 1 : 0, // Se è il capitano
                'cost' => $costs[$memberId], // Costo per ciascun membro
            ]);
        }

        // Calcola la media delle caratteristiche
        $averageCharacteristics = [];
        if ($numMembers > 0) {
            foreach ($totalCharacteristics as $key => $total) {
                $averageCharacteristics[$key] = round($total / $numMembers);
            }
        }

        // Salva le caratteristiche aggregate (sia la somma totale che la media) come JSON nel team
        $team->characteristics = [
            'total' => $totalCharacteristics,
            'average' => $averageCharacteristics
        ];
        $team->save();

        return redirect()->route('events.show', $request->event_id)->with('success', 'Team creato con successo!');
    }
}
