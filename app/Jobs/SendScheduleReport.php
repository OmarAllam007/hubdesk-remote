<?php

namespace App\Jobs;

use App\Report;
use App\ScheduledReport;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendScheduleReport extends Job{


    protected $report;

    public function __construct(ScheduledReport $scheduledReport)
    {
        $this->report = $scheduledReport;

    }

    public function handle()
    {
        $report_to_be_generated = $this->report->report;
        if($report_to_be_generated->type == Report::$CORE_REPORT){

        }

    }
}
