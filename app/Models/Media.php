<?php

namespace App\Models;

use function Illuminate\Events\queueable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['src'];

    protected static function booted()
    {
        static::deleted(queueable(function ($media) {
            Storage::disk('local')->delete($media->original_path);
            Storage::disk('local')->delete($media->thumb_path);
        }));

    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function getSrcAttribute()
    {
        return route('media.show.thumb', $this->name);
    }
}
