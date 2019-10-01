<?php

namespace App;

use App\Reports\CustomReport;
use App\Reports\QueryReport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Carbon last_scheduled
 *
 */
class ScheduledReport extends Model
{
    protected $fillable = ['user_id', 'report_id', 'type', 'to', 'subject',
        'message', 'scheduled_time', 'last_scheduled','format'];

//    protected $dates = ['last_scheduled'];

    protected $casts = [
        'to' => 'array',
        'scheduled_time' => 'array',
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

    function getReportTypeAttribute()
    {
        if ($this->report->type == Report::$CORE_REPORT) {
            $core_report_class = $this->report->core_report->class_name;
            return new $core_report_class($this->report);
        } elseif ($this->report->type == Report::$QUERY_REPORT) {
            return new QueryReport($this->report);
        } else {
            return new CustomReport($this->report);
        }
    }

    function shouldSend()
    {

        $now = Carbon::parse(Carbon::now()->format('Y-m-d h:ia'));

        if ($this->last_scheduled) {
            $scheduled_date = $this->next_schedule_date;
        } else {
            $scheduled_date = $this->scheduled_date;
        }

        $not_generated = ($now->greaterThan($scheduled_date) && $now->greaterThan($this->last_scheduled));
        $should_send = ($scheduled_date && $scheduled_date->equalTo($now));

        if ($should_send || $not_generated) {
            return true;
        }

        return false;
    }


    function getNextScheduleDateAttribute()
    {
        $last_date = Carbon::parse($this->last_scheduled);

        if (!$last_date) {
            return;
        }

        if ($this->type == self::$DAILY) {
            return $last_date->addDay();
        } elseif ($this->type == self::$WEEKLY) {
            return $last_date->addWeek();
        } elseif ($this->type == self::$MONTHLY) {
            return $last_date->addMonth();
        }

    }


    public function getScheduledDateAttribute()
    {
        if ($this->type == self::$ONCE) {
            $scheduled_date = $this->once_date;
        } elseif ($this->type == self::$DAILY) {
            $scheduled_date = $this->daily_date;
        } elseif ($this->type == self::$WEEKLY) {
            $scheduled_date = $this->weekly_date;
        } else {
            $scheduled_date = $this->monthly_date;
        }

        return Carbon::parse($scheduled_date->format('Y-m-d h:ia'));
    }

    private function getOnceDateAttribute()
    {
        return Carbon::parse($this->scheduled_time['date']);
    }

    private function getDailyDateAttribute()
    {
        $day = Carbon::now()->day;
        $hour = $this->scheduled_time['hour'];
        $minutes = $this->scheduled_time['minutes'];

        return Carbon::create()->day($day)->hour($hour)->minute($minutes);
    }

    private function getWeeklyDateAttribute()
    {
        $scheduled_days = $this->scheduled_time['days'];

        $day_of_week = Carbon::now()->dayOfWeek;

        if (array_has($scheduled_days, $day_of_week)) {
            $hour = $this->scheduled_time['hour'];
            $minutes = $this->scheduled_time['minutes'];

            return Carbon::create()->day(Carbon::now()->day)->hour($hour)->minute($minutes);
        }

    }

    private function getMonthlyDateAttribute()
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


    function getReportTypeStrAttribute()
    {
        $types = [
            self::$ONCE => 'Once',
            self::$DAILY => 'Daily',
            self::$WEEKLY => 'Weekly',
            self::$MONTHLY => 'Monthly',
        ];
        return $types[$this->type];
    }
}
