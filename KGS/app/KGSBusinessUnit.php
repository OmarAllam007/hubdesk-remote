<?php

namespace KGS;

use App\BusinessUnit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class KGSBusinessUnit extends Model
{
    protected $table = 'kgs_business_units';

    protected $fillable = ['business_unit_id'];

    function business_unit()
    {
        return $this->belongsTo(BusinessUnit::class, 'business_unit_id');
    }

    function scopeBusinessUnits(Builder $q)
    {
        return $q->whereHas('business_unit', function ($query) {
            return $query->orderBy('name');
        });
    }
}
