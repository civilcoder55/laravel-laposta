<?php

namespace App\Models;

use App\Models\Casts\DiffForHumans;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    use HasFactory;

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => DiffForHumans::class,
    ];

}
