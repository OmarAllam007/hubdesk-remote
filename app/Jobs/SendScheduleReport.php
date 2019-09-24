<?php

namespace App\Jobs;

use App\Report;
use App\Reports\CustomReport;
use App\Reports\QueryReport;
use App\ScheduledReport;
use App\User;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Mail\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use League\Flysystem\File;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;


class SendScheduleReport extends Job
{


    protected $report;

    public function __construct(ScheduledReport $scheduledReport)
    {
        $this->report = $scheduledReport;

    }

    public function handle()
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        ini_set('max_execution_time', 0);


        if ($this->report->type == Report::$CORE_REPORT) {
            $core_report_class = $this->report->report->core_report->class_name;
            $report = new $core_report_class($this->report->report);
        } elseif ($this->report->report->type == Report::$QUERY_REPORT) {
            $report = new QueryReport($this->report->report);
        } else {
            $report = new CustomReport($this->report->report);
        }

        $this->sendReport($report);
    }

    private function getFile($report)
    {
        if ($this->report->format == ScheduledReport::$PDF) {
            return $this->report->report_type->pdf();
        } elseif ($this->report->format == ScheduledReport::$EXCEL) {
            return $this->report->report_type->excel();
        }
    }

    private function sendReport($report)
    {
        $users = User::whereIn('id', $this->report->to)->pluck('email')->filter()->toArray();

        $file = $this->getFile($report);

        if ($this->report->format == ScheduledReport::$PDF) {
            $this->sendPDF($file, $report, $users);

        } elseif ($this->report->format == ScheduledReport::$EXCEL) {
            $this->sendExcel($file, $report, $users);
        }

        $this->report->update(['last_scheduled' => Carbon::now()->toDateTimeString()]);
    }

    private function sendPDF($file, $report, $users)
    {
        \Mail::to($users)->send(new \App\Mail\ScheduledReport($this->report, $report, $file));
        unlink($file);
    }

    private function sendExcel($file, $report, $users)
    {
        /** @var LaravelExcelWriter $file */

        $file_path = $file->store('xlsx', storage_path('excel_reports'));
        $path = $file_path->storagePath . '/' . $file->title . '.' . $file->ext;

        \Mail::to($users)->send(new \App\Mail\ScheduledReport($this->report, $report, $path));
        unlink($path);
    }
}
