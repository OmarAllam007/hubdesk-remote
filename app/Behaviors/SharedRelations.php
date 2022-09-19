<?php

namespace App\Behaviors;

use App\Availability;
use App\BusinessRule;
use App\ServiceLimit;
use Illuminate\Database\Eloquent\Builder;

trait  SharedRelations
{

    function limitations()
    {
        return $this->hasMany(ServiceLimit::class, 'level_id')
            ->where('level', get_class($this));
    }


    function availabilities()
    {
        return $this->hasMany(Availability::class, 'level_id');
    }

    function getBusinessRulesAttribute()
    {
        return BusinessRule::with('criteria')
            ->whereHas('criteria.criteria', function (Builder $q) {
                $q->where(function ($q) {
                    $q->where('field', strtolower(class_basename(self::class)) . '_id')
                        ->where('label', 'like', '%' . $this->name . '%');
                });

            })->get();
    }
}