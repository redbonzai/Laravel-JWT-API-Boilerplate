<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
    protected $fillable = [
        'user_id',
        'posts_id',
        'comments_id',
        'dislike'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function post()
    {
        return $this->belongsTo(Posts::class, 'posts_id', 'id');
    }

    public function comment()
    {
        return $this->belongsTo(Comments::class, 'comments_id', 'id');
    }
}
