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
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'societyId');
    }

    /**
     * Get the societies associated with a user based on the memberList.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getSocietiesForUser($userId)
    {
        return self::whereJsonContains('memberList', $userId)->get();
    }
}
