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

        $loader = new \PHPExcel_Reader_Excel2007();
        $loader->setReadDataOnly(true);
        $excelFile = $loader->load($this->file);
        $sheet = $excelFile->getSheet(0);

        $rows = $sheet->getRowIterator(2);
        foreach ($rows as $row) {
            $cells = $row->getCellIterator();
            $data = $this->getDataOfCells($cells);

            if (!array_filter($data)) {
                continue;
            }

            $user = User::whereNotNull('email')->where('email', $data[19])->first();

            if (!$user) {
                $username = explode(" ", $data[3]);
                $userByName = User::whereRaw("MATCH(name) AGAINST ('+$username[0] +$username[1]' IN BOOLEAN MODE)")->first();
                if ($userByName) {
                    $this->updateUser($userByName, $data);
                } else {
                    $this->createUser($data);
                }

            } else {
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
        $businessUnitId = $this->businessUnits->get($data[18]);
        $data = [
            'business_unit_id' => $businessUnitId,
            'job' => $data[13],
            'department_id' => $this->getDepartmentId($data[14], $businessUnitId),
            'employee_id' => $data[2],
            'email' => $user->email ? $user->email : $data[19] == "" ? null : $data[19] ,
            'extra_fields' => $this->extraFields($data),
        ];

        if ($user->password == "") {
            $data['password'] = bcrypt('kifah1234');
        }

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
        $fields['nationality'] = $data[5];
        $fields['id_number'] = $data[6];
        $fields['religion'] = $data[7];
        $fields['passport_number'] = $data[8];
        $fields['gender'] = $data[9] ?? '';
        $fields['martital_status'] = $data[10] ?? '';
        $fields['no_of_children'] = $data[11] ?? '';
        $fields['hire_date'] = date('Y-m-d', \PHPExcel_Shared_Date::ExcelToPHP($data[12])) ?? '';
        $fields['personal_area'] = $data[15];
        $fields['personal_sub_area'] = $data[16];
        $fields['cr_file_no'] = $data[25];

        return $fields;
    }
}
