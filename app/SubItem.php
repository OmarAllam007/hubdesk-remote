<?php

namespace App;

use App\Behaviors\ServiceConfiguration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubItem extends Model
{
    use SoftDeletes, ServiceConfiguration;

    protected $fillable = [
        'item_id', 'name', 'description', 'service_cost', 'order', 'is_disabled'
    ];

    function item()
    {
        return $this->belongsTo(Item::class);
    }


    public function service_user_groups()
    {
        return $this->hasMany(ServiceUserGroup::class, 'level_id')
            ->where('level', ServiceUserGroup::$SUB_ITEM);
    }
}
