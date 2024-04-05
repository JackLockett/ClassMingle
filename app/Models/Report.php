<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';
    protected $fillable = [
        'user_id',
        'post_id',
        'society_id',
        'comment_id',
        'reportType',
        'reportReason'
    ];
}
