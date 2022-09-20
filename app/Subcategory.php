<?php

namespace App;

use App\Behaviors\Listable;
use App\Behaviors\ServiceConfiguration;
use App\Behaviors\SharedRelations;
use Illuminate\Database\Eloquent\Builder;
use KGS\Requirement;

/**
 * App\Subcategory
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $name
 * @property string $description
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Item[] $items
 * @property-read \App\Category $category
 * @method static \Illuminate\Database\Query\Builder|\App\Subcategory whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subcategory whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subcategory whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subcategory whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subcategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subcategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subcategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subcategory selection($empty = false)
 * @mixin \Eloquent
 */
class Subcategory extends KModel
{
    use Listable, ServiceConfiguration, SharedRelations;

    protected $fillable = ['category_id', 'name', 'description', 'service_request', 'service_cost',
        'notes', 'service_type', 'is_disabled', 'business_service_type', 'logo'];

    public function items()
    {
        return $this->hasMany(Item::class, 'subcategory_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    function levels()
    {
        return $this->hasMany(ApprovalLevels::class, 'level_id')->where('type', 2);
    }

    public function service_user_groups()
    {
        return $this->hasMany(ServiceUserGroup::class, 'level_id')->where('level', ServiceUserGroup::$SUBCATEGORY);
    }

    function fees()
    {
        return $this->hasMany(AdditionalFee::class, 'level_id')->where('level', AdditionalFee::SUBCATEGORY);
    }

    function complaint()
    {
        return $this->hasOne(Complaint::class, 'level_id')->where('level', 'App\Subcategory');
    }

    function scopeActive($query)
    {
        return $query->where('is_disabled', 0);
    }

    public function scopeCanonicalList(Builder $query)
    {
        $subcategories = $query->whereHas('category', function ($q) {
            $q->where('is_disabled', 0)->orderBy('name');
        })
            ->get()
            ->map(function ($subcategory) {
                $subcategory->name = ($subcategory->category->business_unit->name ?? "") . ' > ' .
                    $subcategory->category->name . ' > ' . $subcategory->name;
                return $subcategory;
            });


        return $subcategories->sortByDesc('name');
    }

    public function getLevelArrowNameAttribute()
    {
        return $this->category->name . ' > ' . $this->name;
    }

    public function scopeGeneralService(Builder $query)
    {
        return $query->whereHas('category', function ($q) {
            return $q->where('business_unit_id', env('GS_ID'));
        });
    }

    public function canonicalName()
    {
        return $this->category->name . ' > ' . $this->name;
    }

    function custom_fields()
    {
        return $this->morphMany(CustomField::class, 'level', 'level')->orderBy('order');
    }

    public function requirements()
    {
        return $this->hasMany(Requirement::class, 'reference_id')
            ->where('reference_type', Requirement::$types['Subcategory']);
    }

    public function getUrlAttribute()
    {
        $basename = str_replace('+', ' ', urlencode(basename($this->logo)));
        $dirname = dirname($this->logo);
        $path = $dirname . '/' . $basename;
        return url('/storage' . $path);
    }
}
