<?php

namespace App\Listeners;

use App\BusinessUnit;
use App\CustomField;
use App\Department;
use App\Jobs\TicketReplyJob;
use App\Ticket;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;

class CreateUserTicketListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Ticket $ticket
     * @return void
     */
    public function handle($ticket)
    {
        if ($ticket->subcategory_id == 662) {
            $request = request();
            $fields = collect();

            foreach ($request->get('cf', []) as $key => $item) {
                if ($item) {
                    $field = CustomField::find($key)->name ?? '';
                    $fields->put($field, $item);
                }
            }

            $this->findUserInformationToCreate($ticket, $fields);
        }
    }

    /**
     * @param \Illuminate\Support\Collection $fields
     */
    private function findUserInformationToCreate($ticket, $fields)
    {
        $user = User::where('employee_id', $fields->get('Employee ID'))
            ->orWhere('email', $fields->get('Email'))->first();

        if (!$user && $this->userValidEmail($fields->get('Email'))) {

            if (!$this->getBusinessUnitId($fields->get('Company'))) {
                return;
            }

            $user = User::create([
                'name' => $fields->get('Name (In English)'),
                'email' => $fields->get('Email'),
                'employee_id' => $fields->get('Employee ID'),
                'login' => $fields->get('Employee ID'),
                'job' => $fields->get('Job Title'),
                'business_unit_id' => $this->getBusinessUnitId($fields->get('Company')),
                'department_id' => $this->getDepartmentId($fields->get('Department')),
                'manager_id' => $this->getManagerId($fields->get('Direct Manager Email')),
                'password' => bcrypt(env('DEFAULT_PASS'))
            ]);

            $this->updateTicket($ticket, $user);
        }
    }

    private function getBusinessUnitId($businessUnitName)
    {
        $businessUnit = BusinessUnit::where('code', substr($businessUnitName, 0, 4))->first();
        return $businessUnit ? $businessUnit->id : 0;
    }

    private function getDepartmentId($departmentName)
    {
        $department = Department::where('name', $departmentName)->first();
        return $department ? $department->id : null;
    }

    private function getManagerId($managerEmail)
    {
        $validator = \Validator::make(['email' => $managerEmail], [
            'email' => 'required|email'
        ]);

        if ($validator->passes()) {
            $manager = User::whereEmail($managerEmail)->first();
            return $manager ? $manager->id : null;
        }

        return null;
    }

    /**
     * @param Ticket $ticket
     */
    private function updateTicket($ticket, $user)
    {
        $data = ['content' => "<p>username: $user->employee_id</p><p>password: kifah1234</p>",
            'status_id' => 7, 'user_id' => $ticket->technician_id];

        // Fires creating event in \App\Providers\TicketReplyEventProvider
        $reply = $ticket->replies()->create($data);
    }

    private function userValidEmail($email): bool
    {
        $validator = \Validator::make(['email' => $email], [
            'email' => 'required|email'
        ]);

        return !$validator->fails();
    }

}
