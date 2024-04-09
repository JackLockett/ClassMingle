<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    protected $table = 'users';

    protected $fillable = [
        'email', 'username', 'password', 'role', 'avatar', 'bio', 'university', 'verified', 'verify_token', 'reset_token'
    ];

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
                    ->wherePivot('status', 'accepted');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isBanned()
    {
        return $this->ban()->exists();
    }

    public function ban()
    {
        return $this->hasOne(Ban::class);
    }


}
