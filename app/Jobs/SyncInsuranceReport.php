<?php

namespace App\Jobs;

use App\Reports\QueryReport;
use Google\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Revolution\Google\Sheets\Sheets;

class SyncInsuranceReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;




    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $report = \App\Report::find('131');
        $r = new QueryReport($report);

        $client = new Client();
        $client->setScopes([\Google\Service\Sheets::DRIVE, \Google\Service\Sheets::SPREADSHEETS]);

        $client->setClientId(env("google_client_id"));
        $client->setClientSecret(env("google_client_secret"));
        $client->setAuthConfig("storage/credentials.json");
        $service = new \Google\Service\Sheets($client);

        $sheets = new Sheets();
        $sheets->setService($service);

        /** @var Collection $rows */
        $rows = $r->data;

        $sheets->spreadsheet(env('POST_SPREADSHEET_ID'))->sheet('Medical_Hubdesk')->range("A2:R2000")->clear();

        $currentRow = 2;
        foreach ($rows as $row){
            $sheets->spreadsheet(env('POST_SPREADSHEET_ID'))->sheet('Medical_Hubdesk')
                ->range("A$currentRow:S$currentRow")
                ->update([
                    [
                        $row->{"Hubdesk ID"} ?? "",
                        $row->{"Category"} ?? "",
                        $row->{"SubCategory"} ?? "",
                        $row->{"Item"} ?? "",
                        $row->{"SubItem"} ?? "",
                        $row->{"Requester"} ?? "",
                        $row->{"Requester SAP ID"} ?? "",
                        $row->{"Company"} ?? "",
                        $row->{"Technician"} ?? "",
                        $row->{"Status"} ?? "",
                        $row->{"Received Date"} ?? "",
                        $row->{"Month"} ?? "",
                        $row->{"Year"} ?? "",
                        $row->{"Due (Action) Date"} ?? "",
                        $row->{"Time Spent"} ?? "",
                        $row->{"Closed Date"} ?? "",
                        $row->{"Requirement Date"} ?? "",
                        $row->{"Feedback Date"} ?? "",
                        $row->{"Performance"} ?? "",
                    ]
                ]);
            ++$currentRow;
            sleep(1);
        }
    }
}
