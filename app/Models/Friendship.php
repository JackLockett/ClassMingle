<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    protected $fillable = [
        'user_id',
        'friend_id',
        'status',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }

    public function accept()
    {
        $this->status = 'accepted';
        $this->save();
    }

    public function deny()
    {
        $this->status = 'denied';
        $this->save();
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    public function isDenied()
    {
        return $this->status === 'denied';
    }
}
