<?php

namespace App\Mail;

use App\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ScheduledReport extends Mailable
{
    use Queueable, SerializesModels;


    protected $report;
    protected $scheduled_report;
    protected $file;

    public function __construct(\App\ScheduledReport $scheduled_report,$report, $file)
    {
        $this->report = $report;
        $this->scheduled_report = $scheduled_report;
        $this->file = $file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       $report = $this->report;

        if($this->scheduled_report->format == \App\ScheduledReport::$PDF){
            $as =  $report->report->title.'.pdf';
            $mime = 'application/pdf';
        }else{
            $as =  $report->report->title.'.xlsx';
            $mime = 'application/xlsx';
        }

        $report_info = $report->report;
        return $this->subject($report->report->title)->attach($this->file ,[
            'as' =>$as,
            'mime' => $mime,
        ])->markdown('emails.report.scheduled_template',['report'=>$report_info]);


    }
}
