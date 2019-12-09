<?php

namespace App\Jobs;

use App\BusinessUnit;
use App\Department;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UploadUsersJob extends Job
{

    /**
     * Create a new job instance.
     *
     * @return void
     */
    /** @var Collection $businessUnits */

    private $file;
    private $businessUnits;

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
        $this->businessUnits = BusinessUnit::all()->pluck('id', 'code');

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($this->file);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($this->file);

        $sheet = $spreadsheet->getActiveSheet();

        foreach ($sheet->getRowIterator(1,1) as $row) {
             $cells = $row->getCellIterator();
            $data = $this->getDataOfCells($cells);

             if($data[2] != 'Employee Number' && $data[40] != 'Company Code'){
                 return;
             }
        }


        foreach ($sheet->getRowIterator(2) as $row) {

            $cells = $row->getCellIterator();
            $data = $this->getDataOfCells($cells);
            $user = User::whereNotNull('employee_id')->where('employee_id', $data[2])->first();

            if ($user) {
                $this->updateUser($user, $data);
            }

        }
    }

    private function getDataOfCells($cells)
    {
        /** @var Cell $cell */
        $data = [];
        foreach ($cells as $cell) {
            $data[] = $cell->getValue();
        }

        return array_map('trim', $data);
    }

    private function createUser(array $data)
    {
        $businessUnitId = $this->businessUnits->get($data[18]);

        User::create([
            'name' => $data[3],
            'email' => $data[19] == "" ? null : $data[19],
            'password' => bcrypt('kifah1234'),
            'business_unit_id' => $businessUnitId,
            'job' => $data[13],
            'department_id' => $this->getDepartmentId($data[14], $businessUnitId),
            'employee_id' => $data[2],
            'extra_fields' => $this->extraFields($data)
        ]);
    }

    private function updateUser($user, $data)
    {
        $businessUnitId = $this->businessUnits->get($data[40]);

        $data = [
            'business_unit_id' => $businessUnitId,
            'job' => $data[30],
            'department_id' => $this->getDepartmentId($data[33], $businessUnitId),
            'extra_fields' => $this->extraFields($data),
        ];

        $user->update($data);
    }

    private function getDepartmentId($departmentName, $businessUnitId)
    {

        $department = Department::where('name', $departmentName)->first();
        if (!$department) {
            $department = Department::create(['name' => $departmentName, 'business_unit_id' => $businessUnitId]);
        }

        return $department->id;
    }

    private function extraFields($data)
    {
        $fields = [];
        $fields['emis_id'] = $data[1];
        $fields['ar_name'] = $data[4];
        $fields['nationality'] = $data[6];
        $fields['id_number'] = $data[6];
        $fields['religion'] = $data[11];
        $fields['passport_number'] = $data[17];
        $fields['gender'] = $data[21] ?? '';
        $fields['marital_status'] = $data[22] ?? '';
        $fields['no_of_children'] = $data[23] ?? '';
        $fields['hire_date'] = Date::excelToDateTimeObject($data[26])->format('Y-m-d') ?? '';
        $fields['personal_area'] = $data[41];
        $fields['personal_sub_area'] = $data[42];
        $fields['cr_file_no'] = $data[60];

        return $fields;
    }
}
