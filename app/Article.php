<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';
    
    protected $fillable = [
        'id',
        'title',
        'image_url',
        'description',
        'user_id',
    ];

    public $timestamps = true;
}
