<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'published'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comments::class, 'posts_id', 'id');
    }

    public function likes()
    {
        return $this->hasMany(Likes::class, 'posts_id', 'id');
    }
}
