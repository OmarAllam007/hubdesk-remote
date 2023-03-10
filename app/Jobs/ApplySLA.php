<?php

namespace App\Jobs;

use App\Sla;
use App\Ticket;
use Carbon\Carbon;

class ApplySLA extends MatchCriteria
{
    /** @var Ticket */
    protected $ticket;

    /** @var Carbon */
    protected $workStartTime;

    /** @var Carbon */
    protected $workEndTime;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;

        $this->workStartTime = Carbon::parse(config('worktime.end'));
        $this->workEndTime = Carbon::parse(config('worktime.end'));
    }

    public function handle()
    {
        if (!$this->ticket->shouldApplySla()) {
            return false;
        }
        $sla = $this->fetchSLA();

        if ($sla) {
            $this->ticket->sla_id = $sla->id;
            $this->ticket->due_date = $this->calculateTime($sla->due_days, $sla->due_hours, $sla->due_minutes, $sla->critical);
            $this->ticket->first_response_date = $this->calculateTime($sla->response_days, $sla->response_hours, $sla->response_minutes);
        } else {
            $this->ticket->sla_id = null;
            $this->ticket->due_date = null;
            $this->ticket->first_response_date = null;
        }

        $this->ticket->setApplySla(false)->stopLog(true);
        $this->ticket->save();
    }

    public function fetchSLA()
    {
        $agreements = Sla::with('criterions')->get();
        foreach ($agreements as $sla) {
            if ($this->match($sla)) {
                return $sla;
            }
        }

        return false;
    }

    protected function calculateTime($days, $hours, $minutes, $critical = false)
    {
        $date = clone $this->ticket->start_time;

        $workStart = config('worktime.start');
        $workEnd = config('worktime.end');


        /** @var Carbon $date */
        if ($critical) {
            $date->addDays($days);
        } else {
            $date->addWeekdays($days);
        }

        $date->addHours($hours);
        $date->addMinute($minutes);

        $end = clone($date);
        $end->setTimeFromTimeString($workEnd);

        if ($date->gt($end)) {
            // If the due date is after working hours, move to next day
            $diff = $date->diffInMinutes($end);
            $date->addDay();
            $date->setTimeFromTimeString($workStart)->addMinutes($diff);
        }

        while (!$critical && $date->isWeekend()) {
            // As long as due date is in a weekend move due time to next day
            $date->addDay();
        }

        return $date;
    }
}
