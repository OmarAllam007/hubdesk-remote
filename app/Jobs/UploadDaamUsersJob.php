<?php

namespace App\Jobs;

use App\BusinessUnit;
use App\Department;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UploadDaamUsersJob extends Job
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $file;
    /**
     * @var array
     */
    protected $header;
    /**
     * @var array
     */
    private $other_info;
    /**
     * @var \Illuminate\Support\Collection
     */
    private $businessUnits;

    public function __construct($file)
    {
        $this->file = $file;
        $this->other_info = collect();
    }


    public function handle()
    {
        set_time_limit(1800);

        $this->businessUnits = BusinessUnit::all()->pluck('id', 'name');

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($this->file);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($this->file);

        $sheet = $spreadsheet->getActiveSheet();

        $checkRow = $sheet->getRowIterator(1, 1)->current();
        $dataCells = $checkRow->getCellIterator();
        $data = $this->getDataOfCells($dataCells);

        if ($data[1] != 'Employee ID' && $data[2] != 'Company Name') {
            return;
        }

        $this->header = $data;

        $this->other_info->put(5, $this->header[5]);
        $this->other_info->put(6, $this->header[6]);
        $this->other_info->put(7, $this->header[7]);
        $this->other_info->put(8, $this->header[8]);

//        $notInUsersDB = [];
        foreach ($sheet->getRowIterator(2) as $row) {
            $cells = $row->getCellIterator();
            $data = $this->getDataOfCells($cells);
            $user = User::where('employee_id', $data[1])->first();

            if ($user) {
                $this->updateUser($user, $data);
            }
        }

//        dd($notInUsersDB);

    }


    private function getDataOfCells($cells)
    {
        /** @var Cell $cell */
        $data = [];
        foreach ($cells as $cell) {
            $value = $cell->getFormattedValue();
            if ($cell->getColumn() == 'F' && $cell->getRow() > 1){
                if($value != ''){
                    $data[] = Date::excelToDateTimeObject($value)->format('Y-m-d');
                }
            }else{
                $data[] = $value;
            }
        }

        return array_map('trim', $data);
    }

    private function updateUser(User $user, $data)
    {

        $businessUnitId = $this->businessUnits->get(trim($data[4]));
        $directManager = User::where('employee_id', trim($data[11]))->first();

        $user->update([
            'name' => $data[2],
            'job' => $data[3],
            'business_unit_id' => $businessUnitId,
            'email' => trim($data[10]),
            'manager_id' => $directManager->id ?? null,
            'department_id' => $this->getDepartmentId($data[9]),
            'extra_fields' => $this->extraFields($data),
        ]);
    }

    private function getDepartmentId($name)
    {
        $department = Department::where('name', trim($name))->first();

        if (!$department) {
            $newDepartment = Department::create([
                'business_unit_id' => 59,//daam
                'name' => $name
            ]);
            return $newDepartment->id;
        }

        return $department->id;
    }

    private function extraFields($data)
    {
//        $data_fields = array_splice($data, 7);
        $fields = [];

        foreach ($this->other_info as $key => $header_name) {
            $fields[snake_case(strtolower($header_name))] = $data[$key];
        }

        return $fields;
    }
}
