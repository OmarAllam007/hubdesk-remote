<?php

namespace App\Jobs;

use App\BusinessUnit;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncUsersWithGoogleSheet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public $timeout = 120;

    protected $data;
    protected $businessUnits;

    public function __construct($data)
    {
        $this->data = $data;
        $this->businessUnits = BusinessUnit::all()->pluck('id', 'code');
    }

    function handle()
    {
        foreach ($this->data as $index => $user) {
            if ($index == 0) {
                continue;
            }
            $businessUnitId = $this->businessUnits->get($user[2]);

            $user = User::where('employee_id', $user[0])->update([
                'name' => $user[1],
                'business_unit_id' => $businessUnitId,
                'job' => $user[4],
                'department_id' => User::getDepartment(trim($user[3]), $businessUnitId),
                'manager_id' => User::getDirectManager($user[6]),
                'extra_fields' => $this->extraFields(array_splice($user, 7)),
            ]);
        }
    }

    private function extraFields($data)
    {
        $otherData = array_splice($this->data[0], 7);
        $fields = [];

        foreach ($otherData  as $key => $header_name) {
            $fields[snake_case(strtolower($header_name))] = $data[$key];
        }

        return $fields;
    }
}
