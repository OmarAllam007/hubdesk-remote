<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessCardUserAdminPermissions extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'show', 'edit', 'delete', 'create'];


}
