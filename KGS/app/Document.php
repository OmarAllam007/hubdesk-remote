<?php

namespace KGS;

use App\BusinessUnit;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['business_unit_id', 'name', 'start_date', 'end_date', 'last_updated_by', 'path'];

    protected $dates = ['start_date','end_date'];

    function business_unit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    function getLastUpdatedAttribute()
    {
        if ($this->last_updated_by) {
            return User::find($this->last_updated_by);
        }
        return '';
    }

    public function getUrlAttribute()
    {
        $basename = str_replace('+', ' ', urlencode(basename($this->path)));
        $dirname = dirname($this->path);
        $path = $dirname . '/' . $basename;
        return url('/storage/' . $path);
    }
}
