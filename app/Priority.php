<?php

namespace App;

use App\Behaviors\Listable;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Priority
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Priority whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Priority whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Priority whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Priority whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Priority whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Priority whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Priority selection($empty = false)
 * @mixin \Eloquent
 */
class Priority extends KModel
{
    protected $fillable = ['name', 'description'];

    protected $dates = ['created_at', 'updated_at'];

    use Listable;

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