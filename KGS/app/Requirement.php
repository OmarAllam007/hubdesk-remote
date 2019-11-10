<?php

namespace KGS;

use App\Category;
use App\Item;
use App\Subcategory;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{

    const CATEGORY_TYPE = 1;
    const SUBCATEGORY_TYPE = 2;
    const ITEM_TYPE = 3;


    const SERVICE_TYPE = 1;
    const DOCUMENT_TYPE = 2;

    static $types = ['Category' => 1, 'Subcategory' => 2, 'Item' => 3];




    protected $fillable = [
        'reference_type', 'reference_id', 'field', 'operator', 'label', 'value'
        ,'type'
    ];

    function getServiceCostAttribute()
    {
        if ($this->type == self::DOCUMENT_TYPE){
            return;
        }

        if($this->reference_type == self::CATEGORY_TYPE){
            return Category::find($this->value)->service_cost;
        }else if($this->reference_type == self::SUBCATEGORY_TYPE){
            return Subcategory::find($this->value)->service_cost;
        }
        else if($this->reference_type == self::ITEM_TYPE){
            return Item::find($this->value)->service_cost;
        }

        return 0;
    }
}
