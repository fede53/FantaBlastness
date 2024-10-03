<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'characteristics'
    ];

    public function format()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image!=null ? 'members/' . $this->image : null,
            'thumbnail' => $this->image!=null ? 'members/thumbnails/' . $this->image : null,
            'characteristics' => json_decode($this->characteristics, true)

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

    public function formatWithPivot()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image!=null ? 'members/' . $this->image : null,
            'thumbnail' => $this->image!=null ? 'members/thumbnails/' . $this->image : null,
            'characteristics' => json_decode($this->characteristics, true),
            'active' => $this->pivot->active ?? null,
            'cost' => $this->pivot->cost ?? null,
        ];
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_member')
            ->withPivot('active', 'cost')
            ->withTimestamps();
    }
}
