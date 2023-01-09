<?php

namespace App\Jobs;

use App\BusinessUnit;
use App\Department;
use App\LabourOfficeUser;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UploadLabourUsersJob extends Job
{

    /**
     * Create a new job instance.
     *
     * @return void
     */
    /** @var Collection $businessUnits */

    private $file;
    private $header;

    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(1800);

        $this->businessUnits = BusinessUnit::all()->pluck('id', 'code');

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($this->file);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($this->file);

        $sheet = $spreadsheet->getActiveSheet();

        $checkRow = $sheet->getRowIterator(1, 1)->current();
        $dataCells = $checkRow->getCellIterator();
        $data = $this->getDataOfCells($dataCells);


        $this->header = $data;
        $this->other_info = array_splice($this->header, 7);

        foreach ($sheet->getRowIterator(2) as $row) {
            $cells = $row->getCellIterator();
            $data = $this->getDataOfCells($cells);

            $user = LabourOfficeUser::whereNotNull('employee_id')->where('employee_id', $data[0])->first();

            if ($user) {
                $this->updateUser($user, $data);
            }
            else{
                $this->createUser($data);
            }

        }
    }

    private function getDataOfCells($cells)
    {
        /** @var Cell $cell */
        $data = [];
        foreach ($cells as $cell) {
            $data[] = $cell->getFormattedValue();
        }

        return array_map('trim', $data);
    }

    private function createUser(array $data)
    {
        LabourOfficeUser::create([
            'employee_id' => $data[0],
            'ar_name' => $data[1],
            'nationality' => $data[2],
            'building_no' => $data[3],
            'building_name' => $data[4],
            'border_no' => $data[5],
            'iqama_number' => $data[6],
            'job' => $data[7],
            'iqama_expire_date' => $data[8],
            'ksa_entrance_date' => $data[9],
            'employee_type' => $data[10],
            'employee_status' => $data[11],
            'company' => $data[12],
        ]);
    }

    private function updateUser($user, $data)
    {
        $userInfo = [
            'ar_name' => $data[1],
            'nationality' => $data[2],
            'building_no' => $data[3],
            'building_name' => $data[4],
            'border_no' => $data[5],
            'iqama_number' => $data[6],
            'job' => $data[7],
            'iqama_expire_date' => $data[8],
            'ksa_entrance_date' => $data[9],
            'employee_type' => $data[10],
            'employee_status' => $data[11],
            'company' => $data[12],
        ];


        $user->update($userInfo);
    }
}