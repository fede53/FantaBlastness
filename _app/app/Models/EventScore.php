<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'member_id',
        'score',
        'rules',
        'trophies'
    ];

    public function format(): array
    {
        return [
            'id' => $this->id,
            'event_id' => $this->event_id,
            'member_id' => $this->member_id,
            'score' => $this->score,
            'rules' => $this->rules,
            'trophies' => $this->trophies
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($score) {
            $score->rules = json_encode($score->rules);
            $score->trophies = json_encode($score->trophies);
        });

        static::updating(function ($score) {
            $score->rules = json_encode($score->rules);
            $score->trophies = json_encode($score->trophies);
        });
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
