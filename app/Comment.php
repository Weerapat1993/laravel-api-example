<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    
    protected $fillable = [
        'id',
        'comment',
        'article_id',
        'user_id',
    ];

    public $timestamps = true;
}
