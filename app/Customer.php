<?php

namespace App;

use App\Behaviors\Listable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use Listable;
    protected $fillable = ['name', 'mobile', 'phone', 'email', 'city', 'business_unit_id', 'branch_id', 'national_id', 'type'];
    static $Types = [1=>'individual',2=>'corporate'];

    public function scopeQuickSearch(Builder $query)
    {
        if (\Request::has('search')) {
            $query->where(function (Builder $q) {
                $term = '%' . \Request::get('search') . '%';
                $q->where('name', 'LIKE', $term);
            });
        }

        return $query;
    }
    function businessunit(){
        return $this->belongsTo(BusinessUnit::class,'business_unit_id');
    }

    function branch(){
        return $this->belongsTo(Branch::class);
    }

}
