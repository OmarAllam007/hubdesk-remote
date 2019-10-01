<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['title', 'folder_id', 'core_report_id', 'parameters','user_id', 'query','type'];

    public static $CORE_REPORT = 1;
    public static $QUERY_REPORT = 2;
    public static $CUSTOM_REPORT = 3;

    protected $casts = ['parameters' => 'array'];

    function folder()
    {
        return $this->belongsTo(ReportFolder::class);
    }

    function scopeFilter(Builder $query, $folder)
    {
        $query->privileged();

        $query->where('folder_id', $folder)->where('user_id',auth()->id());

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

    function getIsCoreReportAttribute(){
        return $this->type == Report::$CORE_REPORT;
    }


    function getIsQueryReportAttribute(){
        return $this->type == Report::$QUERY_REPORT;
    }

    function getIsCustomReportAttribute(){
        return $this->type == Report::$CUSTOM_REPORT;
    }
}
