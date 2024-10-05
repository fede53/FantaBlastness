<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'member_id',
        'captain',
        'cost'
    ];

    public function format(): array
    {
        return [
            'id' => $this->id,
            'team_id' => $this->team_id,
            'member' => $this->member->format(),
            'captain' => $this->captain,
            'cost' => $this->cost
        ];
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function eventScores()
    {
        return $this->hasMany(EventScore::class, 'member_id', 'member_id');
    }

    public function eventScoreForEvent($eventId)
    {
        return $this->eventScores()->where('event_id', $eventId)->first();
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
