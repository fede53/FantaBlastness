<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'member_id',
        'active',
        'cost',
        'extra',
        'extra_message'
    ];

    public function format(): array
    {
        return [
            'id' => $this->id,
            'event_id' => $this->event_id,
            'member_id' => $this->member_id,
            'active' => $this->active,
            'cost' => $this->cost,
            'extra' => $this->extra,
            'extra_message' => $this->extra_message
        ];
    }

    public function formatWithScore($eventId)
    {
        $score = 0;
        $extra = 0;
        $extraMessage = "";
        $trophies = [];

        $eventScore = EventScore::where('event_id', $eventId)->where('member_id', $this->member->id)->first();
        if($eventScore){
            $eventScore = $eventScore->format();
            $score = $eventScore['score'];
            $trophies = $eventScore['trophies']!=null ? json_decode($eventScore['trophies'], true) : [];
        }

        $eventMember = EventMember::where('event_id', $eventId)->where('member_id', $this->member->id)->first();
        if($eventMember){
            $eventMember = $eventMember->format();
            $extra = $eventMember['extra'];
            $extraMessage = $eventMember['extra_message'];
        }

        return [
            'id' => $this->member->id,
            'name' => $this->member->name,
            'fantaname' => $this->member->fantaname,
            'email' => $this->member->email,
            'image' => $this->member->image!=null ? 'members/' . $this->member->image : null,
            'thumbnail' => $this->member->image!=null ? 'members/thumbnails/' . $this->member->image : null,
            'characteristics' => json_decode($this->member->characteristics, true),
            'score' => $score,
            'extra' => $extra,
            'extra_message' => $extraMessage,
            'trophies' => $trophies
        ];
    }


    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

}
