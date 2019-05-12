<?php

namespace App;

use App\Behaviors\Listable;
use App\Behaviors\ServiceConfiguration;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Item
 *
 * @property integer $id
 * @property integer $subcategory_id
 * @property string $name
 * @property string $description
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Subcategory $subcategory
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereSubcategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Item selection($empty = false)
 * @mixin \Eloquent
 */
class Item extends KModel
{
    use Listable, ServiceConfiguration;

    protected $fillable = ['subcategory_id', 'name', 'description', 'service_request','service_cost'];

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id', 'id');
    }

    function custom_fields()
    {
        return $this->morphMany(CustomField::class,'level', 'level');
    }

    function levels()
    {
        return $this->hasMany(ApprovalLevels::class, 'level_id')->where('type', 3);
    }

    public function scopeCanonicalList(Builder $query)
    {
        $items = $query->with('subcategory')
            ->with('subcategory.category')
            ->get()->map(function ($item) {
                $item->name = "{$item->subcategory->category->name} > {$item->subcategory->name} > {$item->name}";
                return $item;
            });

        return $items->sortBy('name');
    }
}
