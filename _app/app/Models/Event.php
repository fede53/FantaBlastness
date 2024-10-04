<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'characteristic',
        'regulation',
        'instructions',
        'dolphins',
        'image',
        'date_for_partecipate',
        'date_phase_1',
        'date_phase_2'
    ];

    protected function casts(): array
    {
        return [
            'date_for_partecipate' => 'datetime',
            'date_phase_1' => 'datetime',
            'date_phase_2' => 'datetime'
        ];
    }

    public function format(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'characteristic' => $this->characteristic,
            'regulation' => $this->regulation,
            'instructions' => $this->instructions,
            'dolphins' => $this->dolphins,
            'image' => $this->image!=null ? 'events/' . $this->image : null,
            'thumbnail' => $this->image!=null ? 'events/thumbnails/' . $this->image : null,
            'date_for_partecipate' => $this->date_for_partecipate,
            'can_partecipate' => Carbon::parse($this->date_for_partecipate)->greaterThanOrEqualTo(Carbon::now()),
            'date_phase_1' => $this->date_phase_1,
            'date_phase_2' => $this->date_phase_2,
            'members' => $this->members->map->formatWithPivot()->keyBy('id'),
            'rules' => $this->rules->map->format(),
        ];
    }

    public function formatDashboard(): array
    {
        $haveATeam = Team::where("event_id", $this->id)->where("user_id", auth()->user()->id)->exists();

        $bonusRules = $this->rules->filter(function ($rule) {
            return $rule->type == 0;
        })->map->format();

        $malusRules = $this->rules->filter(function ($rule) {
            return $rule->type == 1;
        })->map->format();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'characteristic' => $this->characteristic,
            'regulation' => $this->regulation,
            'instructions' => $this->instructions,
            'dolphins' => $this->dolphins,
            'image' => $this->image!=null ? 'events/' . $this->image : null,
            'thumbnail' => $this->image!=null ? 'events/thumbnails/' . $this->image : null,
            'date_for_partecipate' => $this->date_for_partecipate,
            'haveATeam' => $haveATeam,
            'can_partecipate' => Carbon::parse($this->date_for_partecipate)->greaterThanOrEqualTo(Carbon::now()),
            'date_phase_1' => $this->date_phase_1,
            'date_phase_2' => $this->date_phase_2,
            'members' => $this->members->map->formatWithPivot()->keyBy('id'),
            'bonus' => $bonusRules,
            'malus' => $malusRules,

        ];
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'event_member')
            ->withPivot('active', 'cost')
            ->withTimestamps()->orderBy('cost');
    }

    public function rules()
    {
        return $this->hasMany(EventRule::class);
    }

}
