<?php

namespace App\Jobs;

use App\BusinessUnit;
use App\Role;
use App\Ticket;
use App\TicketApproval;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ApprovalLevels extends Job
{

    private $ticket;
    private $levels;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
        $this->levels = collect();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $specialApprovalServices = env('REQUESTER_AUTO_APPROVAL_SERVICES');// it is only one required service
        // if they asked for more this should get another solution
        if (($specialApprovalServices && $this->ticket->subcategory_id == $specialApprovalServices) && ($this->ticket->requester_id == auth()->id())) {
            dispatch(new ApplySLA($this->ticket));
            dispatch(new ApplyBusinessRules($this->ticket));

            return;
        }

        $bu = BusinessUnit::find($this->ticket->requester->business_unit_id);

        if (env('GS_ID') && $this->ticket->category->business_unit_id == env('GS_ID')) {
            $bu = BusinessUnit::find($this->ticket->business_unit_id);
        }

        if ($bu) {
            $category = $this->ticket->category;
            $subcategory = $this->ticket->subcategory;
            $item = $this->ticket->item;

            if ($item && $item->levels->count()) {
                $this->levels = $this->getLevels($item);
            } else if ($subcategory && $subcategory->levels->count()) {
                $this->levels = $this->getLevels($subcategory);
            } else if ($category && $category->levels->count()) {
                $this->levels = $this->getLevels($category);
            } else {
                dispatch(new ApplySLA($this->ticket));
                dispatch(new ApplyBusinessRules($this->ticket));
            }

//
            $roles = $bu->roles()
                ->whereIn('role_id', $this->levels->toArray())
                ->get()
                ->keyBy('role_id');


            $managerID = $this->ticket->requester->manager->id ?? null;

            foreach ($this->levels as $key => $level) {

                $isDirectManagerRole = Role::where('type', Role::DIRECT_MANAGER_TYPE)
                    ->where('id', $level)->first();


                if ($isDirectManagerRole && $managerID) {
                    $user = $managerID;
                } else {
                    $role = $roles->get($level);
                    $user = $role->user->id;
                }

                $this->ticket->approvals()->create([
                    'creator_id' => $this->ticket->creator_id,
                    'approver_id' => $user,
                    'status' => 0,
                    'stage' => $key + 1,
                    'content' => 'For your Approval'
                ]);
            }
        }
    }

    public function getLevels($model)
    {
        $levels = collect();

        if ($model) {
            if ($model->levels->count()) {
                $model->levels()->each(function ($level) use ($levels) {
                    $levels->push($level->role_id);
                });
            }
        }

        return $levels;
    }
}
