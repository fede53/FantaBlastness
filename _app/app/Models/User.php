<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'surname',
        'role_id',
        'email',
        'password',
        'image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function format()
    {
        return [
            'id' => $this->id,
            'role_id' => $this->role_id,
            'role' => $this->role->name,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'image' => $this->image!=null ? 'users/' . $this->image : null,
            'thumbnail' => $this->image!=null ? 'users/thumbnails/' . $this->image : null,
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
