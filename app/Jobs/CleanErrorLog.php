<?php

namespace App\Jobs;

use App\ErrorLog;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CleanErrorLog implements ShouldQueue
{
    use InteractsWithQueue,  SerializesModels;


    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $weekago = Carbon::today()->subWeek();
        ErrorLog::where('created_at', '<=', $weekago)->forceDelete();
    }
}
