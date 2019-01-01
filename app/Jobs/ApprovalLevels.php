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

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
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
                $levels = $this->getLevels($item);
            } else if ($subcategory && $subcategory->levels->count()) {
                $levels = $this->getLevels($subcategory);
            } else {
                $levels = $this->getLevels($category);
            }

            $bu->roles()->whereIn('role_id', $levels->toArray())->each(function ($role, $key) {
                $this->ticket->approvals()->create([
                    'creator_id' => $this->ticket->technician->id,
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
