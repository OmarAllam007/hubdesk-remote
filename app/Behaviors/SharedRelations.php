<?php
namespace App\Behaviors;

use App\ServiceLimit;

trait  SharedPreferences{

    function limitations()
    {
        return $this->hasMany(ServiceLimit::class,'level_id')
            ->where('level',get_class($this));
    }
}