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
        set_time_limit(1800);

        $this->businessUnits = BusinessUnit::all()->pluck('id', 'code');

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($this->file);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($this->file);

        $sheet = $spreadsheet->getActiveSheet();

        $checkRow = $sheet->getRowIterator(1, 1)->current();
        $dataCells = $checkRow->getCellIterator();
        $data = $this->getDataOfCells($dataCells);

        if ($data[0] != 'Employee Number' && $data[3] != 'Company Code') {
            return;
        }

        foreach ($sheet->getRowIterator(2) as $row) {

            $cells = $row->getCellIterator();
            $data = $this->getDataOfCells($cells);
            $user = User::whereNotNull('employee_id')->where('employee_id', $data[0])->first();

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
        $businessUnitId = $this->businessUnits->get($data[5]);

        User::create([
            'name' => $data[1],
//            'email' => $data[19] == "" ? null : $data[19],
            'login' => $data[0],
            'password' => bcrypt('kifah1234'),
            'business_unit_id' => $businessUnitId,
            'job' => $data[2],
            'department_id' => $this->getDepartmentId($data[14], $businessUnitId),
            'employee_id' => $data[0],
            'extra_fields' => $this->extraFields($data)
        ]);
    }

    private function updateUser($user, $data)
    {
        $businessUnitId = $this->businessUnits->get($data[3]);

        $data = [
            'business_unit_id' => $businessUnitId,
            'department_id' => $this->getDepartmentId($data[4], $businessUnitId),
            'job' => $data[5],
            'manager_id' => $this->getDirectManagerId($data[6]),
            'extra_fields' => array_replace($user->extra_fields ?? [], $this->extraFields($data)),
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
        $fields['leave_balance'] = $data[7] ?? 0;
        $fields['ar_name'] = $data[8] ?? '';
        $fields['nationality'] = $data[9];
        $fields['personal_id'] = $data[10];
        $fields['cost_center'] = $data[11];
//        $fields['emis_id'] = $data[1];
//        $fields['id_number'] = $data[6];
//        $fields['religion'] = $data[11];
//        $fields['passport_number'] = $data[17];
//        $fields['gender'] = $data[21] ?? '';
//        $fields['marital_status'] = $data[22] ?? '';
//        $fields['no_of_children'] = $data[23] ?? '';
//        $fields['hire_date'] = Date::excelToDateTimeObject($data[26])->format('Y-m-d') ?? '';
//        $fields['personal_sub_area'] = $data[42];
//        $fields['cr_file_no'] = $data[60];

        return $fields;
    }

    private function getDirectManagerId($managerId)
    {
        $user = User::where('employee_id', trim($managerId))->first();
        return $user ? $user->id : null;
    }
}
