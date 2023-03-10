<?php

namespace App;

use App\Behaviors\Listable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Role extends Model
{
    use Listable;

    protected $fillable = ['name'];

    const DIRECT_MANAGER_TYPE = 1;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function businessunits()
    {
        return $this->belongsToMany(BusinessUnit::class);
    }

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


}
