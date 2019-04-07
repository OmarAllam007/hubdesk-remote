<?php

namespace KGS;

use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    static $types = ['Category'=> 1,  'Subcategory'=> 2, 'Item'=>3];

    protected $fillable = [
        'reference_type','reference_id','field','operator','label','value'
    ];
}
