<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'data' => 'object',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function accounts()
    {
        return $this->belongsToMany(Account::class);
    }

    public function media()
    {
        return $this->belongsToMany(Media::class);
    }
}
