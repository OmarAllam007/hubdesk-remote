<?php

namespace App\Console\Commands;

use App\Jobs\SendScheduleReport;
use App\ScheduledReport;
use Illuminate\Console\Command;

class ScheduledReportsCommand extends Command
{

    protected $signature = 'reports:schedule';


    protected $description = 'Run Scheduled Reports';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $reports = ScheduledReport::all();

        /** @var TYPE_NAME $report */
        foreach ($reports as $report) {
            if ($report->shouldSend()) {
                dispatch(new SendScheduleReport($report));
            }
        }
    }
}
