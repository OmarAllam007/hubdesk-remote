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
use Maatwebsite\Excel\Writer;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;


class SendScheduleReport extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;


    protected $report;

    public function __construct(ScheduledReport $scheduledReport)
    {
        $this->report = $scheduledReport;

    }

    public function handle()
    {
//        set_time_limit(0);
//        ini_set("memory_limit", -1);
//        ini_set('max_execution_time', 0);

        if ($this->report->report->type == Report::$CORE_REPORT) {
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
        $to = User::whereIn('id', $this->report->to)->get()->filter();
        $cc = $this->report->cc ? User::whereIn('id', $this->report->cc)->get(): [];

        $file = $this->getFile($report);

        if ($this->report->format == ScheduledReport::$PDF) {
            $this->sendPDF($file, $report, $to, $cc);

        } elseif ($this->report->format == ScheduledReport::$EXCEL) {
            $this->sendExcel($file, $report, $to, $cc);
        }

        $this->report->update(['last_scheduled' => Carbon::now()->toDateTimeString()]);
    }

    private function sendPDF($file, $report, $to, $cc)
    {
        if (!$file) {
            return;
        }

        $this->sendMail($report, $file,$to, $cc);
    }

    private function sendExcel($file, $report, $to, $cc)
    {
        /** @var Writer $file */
        if (!$file) {
            return;
        }

//        $file_path = $file->save('xlsx', storage_path('excel_reports'));
//        $path = $file_path->storagePath . '/' . $file->title . '.' . $file->ext;

        $this->sendMail($report, $file, $to, $cc);
    }

    function sendMail($report, $path, $to, $cc)
    {
        \Mail::to($to)->cc($cc)->send(new \App\Mail\ScheduledReport($this->report, $report, $path));
        unlink($path);
    }
}
