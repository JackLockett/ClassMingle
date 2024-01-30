<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';
    protected $fillable = [
        'authorId',
        'societyId',
        'postTitle',
        'postComment',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'authorId');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}
