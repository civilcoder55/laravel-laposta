<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'avatar'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['email_verified_at' => 'datetime'];

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

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'users.' . $this->id;
    }
}
