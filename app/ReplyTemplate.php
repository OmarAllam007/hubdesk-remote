<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReplyTemplate extends Model
{
    protected $fillable = ['user_id', 'title', 'description'];

    function user()
    {
        return $this->belongsTo(User::class);
    }
}
