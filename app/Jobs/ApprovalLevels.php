<?php

namespace App\Jobs;

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
        $bu = $this->ticket->requester->business_unit;

        if ($bu) {
            $category = $this->ticket->category;
            $subcategory = $this->ticket->subcategory;
            $item = $this->ticket->item;

            if ($item && $item->levels->count()) {
                $this->levels = $this->getLevels($item);
            } else if ($subcategory && $subcategory->levels->count()) {
                $this->levels = $this->getLevels($subcategory);
            } else if($category && $category->levels->count()){
                $this->levels = $this->getLevels($category);
            }else{
                dispatch(new ApplySLA($this->ticket));
                dispatch(new ApplyBusinessRules($this->ticket));
            }

            
            $bu->roles()->whereIn('role_id', $this->levels->toArray())->each(function ($role, $key) {
                $this->ticket->approvals()->create([
                    'creator_id' => $this->ticket->creator_id,
                    'approver_id' => $role->user->id,
                    'status' => 0,
                    'stage' => $key + 1,
                    'content' => ''
                ]);
            });

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
