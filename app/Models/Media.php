<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::deleting(function ($media) {
            try {
                Storage::disk('local')->delete("media/{$media->user_id}/original/{$media->name}");
                Storage::disk('local')->delete("media/{$media->user_id}/thumb/{$media->name}");
            } catch (\Throwable $th) {

            }

        });
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
