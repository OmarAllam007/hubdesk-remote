<?php

namespace KGS;

use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{

    const CATEGORY_TYPE = 1;
    const SUBCATEGORY_TYPE = 2;
    const ITEM_TYPE = 3;

    static $types = ['Category' => 1, 'Subcategory' => 2, 'Item' => 3];


    protected $fillable = [
        'reference_type', 'reference_id', 'field', 'operator', 'label', 'value'
    ];

}
