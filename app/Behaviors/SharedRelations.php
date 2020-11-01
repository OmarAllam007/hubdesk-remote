<?php
namespace App\Behaviors;

use App\Availability;
use App\ServiceLimit;

trait  SharedRelations{

    function limitations()
    {
        return $this->hasMany(ServiceLimit::class,'level_id')
            ->where('level',get_class($this));
    }


    function availabilities()
    {
        return $this->hasMany(Availability::class, 'level_id');
    }
}