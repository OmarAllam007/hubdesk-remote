<?php

namespace App\Console\Commands;

use App\Jobs\SyncInsuranceReport;
use App\Reports\QueryReport;
use Google\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Revolution\Google\Sheets\Sheets;

class AutomateInsuranceReportCommand extends Command
{

    protected $signature = 'insurance:sync';

    protected $description = 'sync insurance report of hubdesk to google sheet';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        dispatch(new SyncInsuranceReport());

    }
}
