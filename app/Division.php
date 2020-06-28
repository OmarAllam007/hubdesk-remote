<?php

namespace App;

use App\Behaviors\Listable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use KGS\KGSBusinessUnit;

class Division extends KModel
{
    use Listable;

    protected $fillable = ['name'];

    function companies()
    {
        return $this->hasMany(BusinessUnit::class);
    }

    function getKGSBusinessUnitsAttribute()
    {
        if(!KGSBusinessUnit::all()->count()){
            return;
        }
        return $this->companies()->whereIn('id',KGSBusinessUnit::all()->pluck('business_unit_id')->toArray())->get();
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
