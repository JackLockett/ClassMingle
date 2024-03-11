<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Society extends Model
{
    protected $table = 'societies';
    protected $fillable = [
        'ownerId',
        'societyName',
        'societyDescription',
        'approved',
        'memberList',
        'moderatorList',
    ];

    protected $casts = [
        'moderatorList' => 'array',
        'memberList' => 'array',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'societyId');
    }

    public static function getSocietiesForUser($userId)
    {
        return self::whereJsonContains('memberList', $userId)->get();
    }

    public function getUserRole($userId)
    {
        if ($userId == $this->ownerId) {
            return 'Owner';
        } elseif (in_array($userId, $this->moderatorList)) {
            return 'Moderator';
        } elseif (in_array($userId, $this->memberList)) {
            return 'User';
        }
        return 'Not a Member';
    }
    
    
}
