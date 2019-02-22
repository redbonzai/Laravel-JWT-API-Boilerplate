<?php

namespace App;

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
        return $this->belongsTo('App\User');
    }

    public function post()
    {
        return $this->belongsTo('App\Posts');
    }

    public function comment()
    {
        return $this->belongsTo('App\Comments');
    }
}
