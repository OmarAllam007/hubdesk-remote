<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserComplaint extends Model
{
    protected $fillable = ['ticket_id', 'user_id', 'description'];
}
