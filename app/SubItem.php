<?php

namespace App;

use App\Behaviors\Listable;
use App\Behaviors\ServiceConfiguration;
use App\Behaviors\SharedRelations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubItem extends KModel
{
    use SoftDeletes, ServiceConfiguration, Listable, SharedRelations;

    protected $fillable = [
        'item_id', 'name', 'description', 'service_cost', 'order', 'is_disabled', 'business_service_type', 'logo'
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


    public function scopeCanonicalList(Builder $query)
    {
        $items = $query->with('item')
            ->with('item.subcategory')
            ->with('item.subcategory.category')
            ->get()->map(function ($item) {
                $item->name = "{$item->item->subcategory->category->name} > {$item->item->subcategory->name} > {$item->item->name} > {$item->name}";
                return $item;
            });

        return $items->sortBy('name');
    }
}
