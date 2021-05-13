<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'logs' => 'array',
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

    public function getAccountsIdsAttribute()
    {
        return array_map(function ($item) {
            return $item['id'];
        }, $this->accounts->toArray());
    }

    public function getMediaIdsAttribute()
    {
        // return array_map(function ($item) {
        //     return $item['id'];
        // }, $this->media->toArray());
        return $this->media->toArray();
    }
    public function getScheduleDateAttribute($value)
    {

        return $value ? Carbon::createFromTimestamp($value) : null;
    }

    public function setLogsAttribute($value)
    {
        $old = isset($this->attributes['logs']) ? json_decode($this->attributes['logs']) : [];
        $new = array_merge($value, $old);
        $this->attributes['logs'] = json_encode($new);
    }
}
