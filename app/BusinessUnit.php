<?php

namespace App;

use App\Behaviors\Listable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use KGS\Document;

/**
 * App\BusinessUnit
 *
 * @property integer $id
 * @property string $name
 * @property integer $location_id
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Location $location
 * @method static \Illuminate\Database\Query\Builder|\App\BusinessUnit whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BusinessUnit whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BusinessUnit whereLocationId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BusinessUnit whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BusinessUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BusinessUnit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BusinessUnit selection($empty = false)
 * @mixin \Eloquent
 */
class BusinessUnit extends KModel
{
    use Listable;

    protected $fillable = ['code', 'name', 'location_id', 'logo'];
    protected $appends = ['bu_roles'];


    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
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

    public function categories()
    {
        return $this->hasMany(Category::class, 'business_unit_id', 'id');
    }

    public function roles()
    {
        return $this->hasMany(BusinessUnitRole::class);
    }

    public function getBuRolesAttribute()
    {
        return $this->roles;
    }


    public static function uploadLogo(BusinessUnit $businessUnit, UploadedFile $file)
    {
        $filename = $file->getClientOriginalName();

        $folder = storage_path('app/public/attachments/business_unit/logo/' . $businessUnit->id . '/');
        if (!is_dir($folder)) {
            mkdir($folder, 0775, true);
        }

        $path = $folder . $filename;
        if (is_file($path)) {
            $filename = uniqid() . '_' . $filename;
            $path = $folder . $filename;
        }

        $file->move($folder, $filename);

        $final_path = '/attachments/business_unit/logo/' . $businessUnit->id . '/' . $filename;

        return $final_path;
    }

    public function getUrlAttribute()
    {
        $basename = str_replace('+', ' ', urlencode(basename($this->logo)));
        $dirname = dirname($this->logo);
        $path = $dirname . '/' . $basename;
        return url('/storage' . $path);
    }

    function documents(){
        return $this->hasMany(Document::class);
    }
} 
