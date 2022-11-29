<?php

namespace App\Console\Commands;

use App\Reports\QueryReport;
use Google\Client;
use Revolution\Google\Sheets\Sheets;

use Illuminate\Console\Command;

class MotorsReportCommand extends Command
{

    protected $signature = 'motors:sync';

    protected $description = 'sync report of hubdesk to google sheet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        set_time_limit(6000);
        $report = \App\Report::find('347');
        $r = new QueryReport($report);

        $client = new Client();
        $client->setScopes([\Google\Service\Sheets::DRIVE, \Google\Service\Sheets::SPREADSHEETS]);

        $client->setClientId(env("google_client_id"));
        $client->setClientSecret(env("google_client_secret"));
        $client->setAuthConfig("../storage/credentials.json");
        $service = new \Google\Service\Sheets($client);

        $sheets = new Sheets();
        $sheets->setService($service);

        $rows = $r->data;

        $sheets->spreadsheet(env('POST_SPREADSHEET_ID'))->sheet('Database_Insur')->range("A2:R2000")->clear();

        $currentRow = 2;

        foreach ($rows as $row){

            $sheets->spreadsheet(env('POST_SPREADSHEET_ID'))->sheet('Database_Insur')
                ->range("A$currentRow:R$currentRow")
                ->update([
                    [
                        $row->{"Hubdesk ID"} ?? "",
                        $row->{"Category"} ?? "",
                        $row->{"SubCategory"} ?? "",
                        $row->{"Requester"} ?? "",
                        $row->{"Requester SAP ID"} ?? "",
                        $row->{"Company"} ?? "",
                        $row->{"Division"} ?? "",
                        $row->{"Technician"} ?? "",
                        $row->{"Status"} ?? "",
                        $row->{"Received Date"} ?? "",
                        $row->{"Month"} ?? "",
                        $row->{"Year"} ?? "",
                        $row->{"Due (Action) Date"} ?? "",
                        $row->{"Closed Date"} ?? "",
                        $row->{"Requirement Date"} ?? "",
                        $row->{"Feedback Date"} ?? "",
                        $row->{"Performance"} ?? "",
                        $row->{"Type"} ?? "",
                    ]
                ]);
            ++$currentRow;
            sleep(1);
        }
    }
}
