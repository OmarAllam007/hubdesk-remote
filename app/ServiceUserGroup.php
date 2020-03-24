<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceUserGroup extends Model
{
    protected $table = 'service_user_groups';

    protected $fillable = ['group_id','level','level_id'];

    static $CATEGORY = 1;
    static $SUBCATEGORY = 2;
    static $ITEM = 3;
    static $SUB_ITEM = 4;

}
