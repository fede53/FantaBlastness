<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    // Definisci i campi che possono essere assegnati tramite il form
    protected $fillable = [
        'user_id',
        'event_id',
        'name',
        'characteristics',
    ];

    public function format(): array
    {
        $characteristics = json_decode($this->characteristics, true);

        $teamScore = 0;
        $extraTeamScore = 0;
        $extraArray = [];

        // Calcola il punteggio per ogni membro e accumulalo nel $teamScore
        $members = $this->members->map(function ($member) use (&$teamScore, &$extraTeamScore, &$extraArray) {
            $extra = null;
            $extraMessage = null;
            $eventMember = EventMember::where('event_id', $this->event_id)->where('member_id', $member['member_id'])->first();
            if($eventMember){
                $eventMember = $eventMember->format();
                if($eventMember['active'] && $eventMember['extra']){
                    $extra = $eventMember['extra'];
                    $extraMessage = $eventMember['extra_message'];
                    $extraTeamScore += $eventMember['extra'];
                    $extraArray[] = [
                        'id' => $member['member_id'],
                        'name' => $member['member']['name'],
                        'extra' => $eventMember['extra'],
                        'extra_message' => $eventMember['extra_message']
                    ];
                }
            }
            $eventScore = $member->eventScoreForEvent($this->event_id);
            $score = $eventScore ? $eventScore->score : 0;
            $memberScore = $score;
            if ($score !== null) {
                $memberScore = $member['captain'] ? $score * 2 : $score;
                $teamScore += $memberScore;
            }

            $memberScoreWithExtra = $memberScore;
            if($extra!=null){
                $memberScoreWithExtra = $memberScore + $extra;
            }

            return [
                'id' => $member['id'],
                'team_id' => $member['team_id'],
                'name' => $member['member']['name'],
                'image' => $member['member']['image'] != null ? 'members/' . $member['member']['image'] : null,
                'thumbnail' => $member['member']['image'] != null ? 'members/thumbnails/' . $member['member']['image'] : null,
                'characteristics' => json_decode($member['member']['characteristics'], true),
                'captain' => $member['captain'],
                'cost' => $member['cost'],
                'event_score' => $eventScore ? $eventScore->score : null,
                'extra' => $extra,
                'extra_message' => $extraMessage,
                'score' => $memberScore!=null ? $memberScore : 'NC',
                'scoreWithExtra' => $memberScoreWithExtra!=null ? $memberScoreWithExtra : 'NC'
            ];
        })->keyBy('id');

        // Ritorna il formato con il punteggio della squadra calcolato
        return [
            'id' => $this->id,
            'user' => $this->user->format(),
            'event' => $this->event->format(),
            'name' => $this->name,
            'team_characteristics_total' => array_key_exists('total', $characteristics) ? $characteristics['total'] : null,
            'team_characteristics_average' => array_key_exists('average', $characteristics) ? $characteristics['average'] : null,
            'members' => $members,
            'team_score' => $teamScore,
            'extra_team_score' => $extraTeamScore,
            'final_team_score' => $teamScore + $extraTeamScore,
            'extra_array' => $extraArray
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($meber) {
            $meber->characteristics = json_encode($meber->characteristics);
        });

        static::updating(function ($meber) {
            $meber->characteristics = json_encode($meber->characteristics);
        });
    }

    public function members()
    {
        return $this->hasMany(TeamMember::class)->orderBy('captain', 'desc');
    }

    // Relazione con l'utente
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relazione con l'evento
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
