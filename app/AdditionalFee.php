<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdditionalFee extends Model
{
    protected $table = "additional_fees";
    protected $fillable = ["level", "level_id", "name", "cost"];

    const CATEGORY = 1;
    const SUBCATEGORY = 2;
    const ITEM = 3;

}
