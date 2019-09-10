<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduledReport extends Model
{
    protected $fillable = ['user_id', 'report_id', 'type', 'to', 'subject',
        'message', 'scheduled_time', 'last_scheduled'];

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function report()
    {
        return $this->belongsTo(Report::class);
    }
}
