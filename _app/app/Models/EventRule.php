<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'type',
        'value',
        'characteristic'
    ];

    protected function casts(): array
    {
        return [
            'value' => 'integer',
        ];
    }

    public function format(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'value' => $this->value,
            'characteristic' => $this->characteristic
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

}
