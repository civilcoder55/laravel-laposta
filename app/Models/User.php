<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['email_verified_at' => 'datetime'];

    protected static function booted()
    {
        static::created(function ($user) {
            $user->profile()->create();
        });
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function socialAuthUser()
    {
        return $this->hasMany(SocialAuthUser::class);
    }

    public function session()
    {
        return $this->hasMany(Session::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'users.' . $this->id;
    }

    public function getAccounts()
    {
        return $this->accounts()->orderBy('created_at', 'desc')->paginate(15);
    }

    public function getScheduledPosts()
    {
        return $this->posts()->where(['is_draft' => 0 , 'success'=>null])->get();
    }

}
