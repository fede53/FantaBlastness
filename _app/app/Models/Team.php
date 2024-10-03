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
        return [
            'id' => $this->id,
            'user' => $this->user->format(),
            'event' => $this->event->format(),
            'name' => $this->name,
            'team_characteristics_total' => array_key_exists('total', $characteristics) ? $characteristics['total'] : null,
            'team_characteristics_average' => array_key_exists('average', $characteristics) ? $characteristics['average'] : null,
            'members' => $this->members->map(function ($member) {
                return [
                    'id' => $member['id'],
                    'team_id' => $member['team_id'],
                    'name' => $member['member']['name'],
                    'image' => $member['member']['image']!=null ? 'members/' . $member['member']['image'] : null,
                    'thumbnail' => $member['member']['image']!=null ? 'members/thumbnails/' . $member['member']['image'] : null,
                    'characteristics' => json_decode($member['member']['characteristics'], true),
                    'captain' => $member['captain'],
                    'cost' => $member['cost'],
                ];
            })->keyBy('id'),
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
