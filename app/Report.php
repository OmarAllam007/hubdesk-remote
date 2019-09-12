<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['title', 'folder_id', 'core_report_id', 'parameters'];

    public static $CORE_REPORT = 1;
    public static $QUERY_REPORT = 2;
    public static $CUSTOM_REPORT = 3;

    protected $casts = ['parameters' => 'array'];

    function folder()
    {
        return $this->belongsTo(ReportFolder::class);
    }

    function scopeFilter(Builder $query, $filters)
    {
        $query->privileged();

        if ($filters['folder']) {
            $query->where('folder_id', $filters['folder']);
        }
    }

    function scopePrivileged(Builder $query)
    {

    }

    function core_report()
    {
        return $this->belongsTo(CoreReport::class);
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }

}
