<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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
        Log::info('users.' . $this->id);
        return 'users.' . $this->id;
    }


}
