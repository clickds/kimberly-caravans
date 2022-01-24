<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jdexx\EloquentRansack\Ransackable;

class User extends Authenticatable
{
    use Notifiable;
    use Ransackable;

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'super',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
