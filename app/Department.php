<?php

namespace App;

use App\Behaviors\Listable;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Department
 *
 * @property integer $id
 * @property string $name
 * @property integer $business_unit_id
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\BusinessUnit $business_unit
 * @method static \Illuminate\Database\Query\Builder|\App\Department whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Department whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Department whereBusinessUnitId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Department whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Department whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Department whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Department extends KModel
{
    protected $fillable = ['name', 'business_unit_id'];

    use Listable;

    public function business_unit()
    {
        return $this->belongsTo(BusinessUnit::class);
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
}
