<?php

namespace App;

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
        return $this->belongsTo('App\User');
    }

    public function post()
    {
        return $this->belongsTo('App\Posts');
    }

    public function likes()
    {
        return $this->hasMany('App\Likes');
    }
}
