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
    ];
}
