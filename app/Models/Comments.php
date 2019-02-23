<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $fillable = [
        'user_id', 
        'posts_id', 
        'content',
        'reply_to'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function post()
    {
        return $this->belongsTo(Posts::class, 'posts_id', 'id');
    }

    public function likes()
    {
        return $this->hasMany(Likes::class, 'comments_id', 'id');
    }
}
