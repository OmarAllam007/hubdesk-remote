<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ScheduledReport extends Model
{
    protected $fillable = ['user_id', 'report_id', 'type', 'to', 'subject',
        'message', 'scheduled_time', 'last_scheduled'];

    protected $casts = [
        'to' => 'array',
        'scheduled_time' => 'array'
    ];


    static $PDF = 1;
    static $EXCEL = 2;

    static $ONCE = 1;
    static $DAILY = 2;
    static $WEEKLY = 3;
    static $MONTHLY = 4;

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    function shouldSend()
    {
        $now = Carbon::parse(Carbon::now()->format('Y-m-d h:i'));
        $scheduled_date = $this->getScheduledDate();

        $should_send = ($scheduled_date && $scheduled_date->equalTo($now));

        if ($should_send) {
            return true;
        }

        return false;
    }

    private function getScheduledDate()
    {

        if ($this->type == self::$ONCE) {
            $scheduled_date = $this->getOnceDate();
        } elseif ($this->type == self::$DAILY) {
            $scheduled_date = $this->getDailyDate();
        } elseif ($this->type == self::$WEEKLY) {
            $scheduled_date = $this->getWeeklyDate();
        } else {
            $scheduled_date = $this->getMonthlyDate();
        }

        return Carbon::parse($scheduled_date->format('Y-m-d h:i'));
    }

    private function getOnceDate()
    {
        return Carbon::parse($this->scheduled_time['date']);
    }

    private function getDailyDate()
    {
        $day = Carbon::now()->day;
        $hour = $this->scheduled_time['hour'];
        $minutes = $this->scheduled_time['minutes'];

        return Carbon::create()->day($day)->hour($hour)->minute($minutes);
    }

    private function getWeeklyDate()
    {
        $scheduled_days = $this->scheduled_time['days'];

        $day_of_week = Carbon::now()->dayOfWeek;

        if (array_has($scheduled_days, $day_of_week)) {
            $hour = $this->scheduled_time['hour'];
            $minutes = $this->scheduled_time['minutes'];

            return Carbon::create()->day(Carbon::now()->day)->hour($hour)->minute($minutes);
        }

    }

    private function getMonthlyDate()
    {
        $scheduled_months = $this->scheduled_time['months'];
        $scheduled_day = $this->scheduled_time['day'];
        $scheduled_hour = $this->scheduled_time['hour'];
        $scheduled_minutes = $this->scheduled_time['minutes'];


        $current_month = Carbon::now()->month;

        if (array_has($scheduled_months, $current_month)) {
            return Carbon::create()->month($current_month)->day($scheduled_day)
                ->hour($scheduled_hour)->minute($scheduled_minutes);
        }

    }
}
