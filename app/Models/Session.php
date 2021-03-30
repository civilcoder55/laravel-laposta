<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $hidden = [
        'payload',
    ];
    protected $casts = [
        'id' => 'string',
    ];
}
