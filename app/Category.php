<?php

namespace App;

use App\Behaviors\Listable;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Category
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Subcategory[] $subcategories
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category selection($empty = false)
 * @mixin \Eloquent
 */
class Category extends KModel
{
    use Listable;

    protected $fillable = ['business_unit_id','name', 'description','service_request', 'service_cost'];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class, 'category_id', 'id');
    }

    function custom_fields()
    {
        return $this->morphMany(CustomField::class,'level', 'level');
    }
    public function scopeQuickSearch(Builder $query)
    {
        if (\Request::has('search')) {
            $query->where(function (Builder $q) {
                $term = '%' . \Request::get('search') . '%';
                $q->where('name', 'LIKE', $term);
            });
        }

        return $query;
    }

    
    public function businessunit()
    {
        return $this->belongsTo(BusinessUnit::class, 'business_unit_id', 'id');
    }

    public function scopeCanonicalList(Builder $query)
    {
        $categories = $query->with('business-unit')
            ->orderBy('name')->get()
            ->map(function($category) {
                $category->name = $category->businessunit->name . ' > ' . $category->name;
                return $category;
            });
        
        return $categories->sortBy('name');
    }

    public function canonicalName()
    {
        return $this->businessunit->name . ' > ' . $this->name;
    }

}
