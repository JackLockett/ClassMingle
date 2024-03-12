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
        'pinned',
    ];

    public function society()
    {
        return $this->belongsTo(Society::class, 'societyId');
    }
    

    public function author()
    {
        return $this->belongsTo(User::class, 'authorId');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function isBookmarked()
    {
        return $this->bookmarks()->where('user_id', auth()->id())->exists();
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

}
