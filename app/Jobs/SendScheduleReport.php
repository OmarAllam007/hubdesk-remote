<?php

namespace App\Jobs;

use App\Report;
use App\Reports\QueryReport;
use App\ScheduledReport;
use App\User;
use Barryvdh\DomPDF\PDF;
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


class SendScheduleReport extends Job{


    protected $report;

    public function __construct(ScheduledReport $scheduledReport)
    {
        $this->report = $scheduledReport;

    }

    public function handle()
    {
        set_time_limit(0);
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 0);

        $report_to_be_generated = $this->report->report;

        $r = '';

        if($report_to_be_generated->type == Report::$CORE_REPORT){
            $core_report_class = $report_to_be_generated->core_report->class_name;
            $r = new $core_report_class($report_to_be_generated);
        }elseif ($report_to_be_generated->type == Report::$QUERY_REPORT){
            $r = new QueryReport($report_to_be_generated);
        }else{

        }

        $file  = $this->getFile($r);

        \Mail::send('emails.report.report',['report'=>$r],function ($message) use ($file){
            $message->attachData($file->output(), 'name.pdf')->to('omar.coder007@gmail.com');
        });

//        \Mail::send('emails.report.report',['report'=>$r],function ($message) use ($file){
//            $message->attachData($file->string('xlsx'), 'name.xlsx', [
////                'mime' => 'application/pdf',
//            ])->to('omar.coder007@gmail.com');
//        });

    }

    private function getFile($report)
    {

        if ($this->report->format == ScheduledReport::$PDF) {
            return $report->pdf();
        }elseif ($this->report->format == ScheduledReport::$EXCEL){
            return  $report->excel();
        }
    }
}
