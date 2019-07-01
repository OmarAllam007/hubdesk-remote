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
}