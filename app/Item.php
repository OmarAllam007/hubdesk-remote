<?php

namespace App;

use App\Behaviors\Listable;
use App\Behaviors\ServiceConfiguration;
use App\Behaviors\SharedRelations;
use Illuminate\Database\Eloquent\Builder;
use KGS\Requirement;

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
    use Listable, ServiceConfiguration,SharedRelations;

    protected $fillable = ['subcategory_id', 'name', 'description', 'service_request', 'service_cost',
        'notes' ,'business_service_type','service_type'];

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id', 'id');
    }

    public function subItems()
    {
        return $this->hasMany(SubItem::class);
    }

    public function canonicalName()
    {
        return $this->subcategory->category->name . ' > ' . $this->subcategory->name . '>' . $this->name;
    }

    function custom_fields()
    {
        return $this->morphMany(CustomField::class, 'level', 'level')->orderBy('order');
    }

    function levels()
    {
        return $this->hasMany(ApprovalLevels::class, 'level_id')->where('type', 3);
    }

    function fees()
    {
        return $this->hasMany(AdditionalFee::class, 'level_id')->where('level', AdditionalFee::ITEM);
    }

    function complaint()
    {
        return $this->hasOne(Complaint::class,'level_id')->where('level','App\Item');
    }

    public function service_user_groups()
    {
        return $this->hasMany(ServiceUserGroup::class, 'level_id')->where('level', ServiceUserGroup::$ITEM);
    }

    function scopeActive($query)
    {
        return $query->where('is_disabled', 0);
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

    public function requirements()
    {
        return $this->hasMany(Requirement::class, 'reference_id')->where('reference_type', Requirement::$types['Item']);
    }
}
