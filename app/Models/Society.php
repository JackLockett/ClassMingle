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
}
