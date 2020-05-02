<?php

namespace App;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\KModel
 *
 * @mixin \Eloquent
 */
class KModel extends EloquentModel
{
    use SoftDeletes;

    static $types = [1 => 'Ticket', 2 => 'Task', 3 => 'Both'];
    static $BUSINESS_TYPES = [1 => 'Individual', 2 => 'Corporate'];

    const TICKET_TYPE = 1;
    const TASK_TYPE = 2;
    const BOTH_TYPE = 3;


    const INDIVIDUAL = 1;
    const CORPORATE = 2;


    function scopeActive($query)
    {
        return $query->where('is_disabled', 0);
    }

    function scopeTicketType($query)
    {
        return $query->where('service_type', self::TICKET_TYPE)->orWhere('service_type', self::BOTH_TYPE);
    }

    function scopeTaskType($query)
    {
        return $query->where('service_type', self::TASK_TYPE)->orWhere('service_type', self::BOTH_TYPE);
    }

    function scopeBoth($query)
    {
        return $query->where('service_type', self::BOTH_TYPE);
    }

    function scopeIndividual($query)
    {
        return $query->where('business_service_type', self::INDIVIDUAL)->orWhere('business_service_type',0);
    }

    function scopeCorporate($query)
    {
        return $query->where('business_service_type', self::CORPORATE);
    }
}