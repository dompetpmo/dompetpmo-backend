<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class UserSession extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'user_sessions';

    protected $fillable = [
        'user_id',
        'token_id',
        'device_name',
        'device_type',
        'platform',
        'ip_address',
        'user_agent',
        'is_active',
        'logged_in_at',
        'last_active_at',
        'logged_out_at',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'logged_in_at'   => 'datetime',
        'last_active_at' => 'datetime',
        'logged_out_at'  => 'datetime',
    ];
}
