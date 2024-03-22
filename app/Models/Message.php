<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $fillable = [
        'senderId',
        'receiverId',
        'message',
        'read',
        'deleted'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'senderId');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'receiverId');
    }
}
