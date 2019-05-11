<?php

namespace App\Console\Commands;

use App\Http\Controllers\ApprovalController;
use App\Http\Requests\Request;
use App\Jobs\SendApproval;
use App\Mail\SendNewApproval;
use App\TicketApproval;
use App\TicketLog;
use Carbon\Carbon;
use Illuminate\Console\Command;

class KGSPendingApprovalsNotifications extends Command
{

    protected $signature = 'approvals:pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pending Approvals KGS Notifications';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //get pending approvals only for GS_tickets

        $pending_kgs_approvals = TicketApproval::where('status', 0)
            ->whereHas('ticket.category.businessunit', function ($query) {
                $query->where('id', env('GS_ID'));
            })->get();

        // check if resend not more than < 2
        // send to approver

        /** @var TicketApproval $approval */

        $request = request();
        $now = Carbon::now()->startOfDay();

        foreach ($pending_kgs_approvals as $approval) {
            $approval_dt = $approval->created_at->startOfDay();
            $should_send = in_array($now->diffInDays($approval_dt,false),[1,3]);
            if ($approval->resend < 2) {
                $should_send = in_array($now->diffInDays($approval_dt,false),[1,3]);
                if ($should_send){
                    $approvalController = new ApprovalController();
                    $approvalController->resend($approval, $request);
                }
            }
        }

    }
}
