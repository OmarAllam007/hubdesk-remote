<?php

namespace App\Policies;

use App\User;
use App\TicketApproval;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketApprovalPolicy
{
    use HandlesAuthorization;


    public function delete(User $user, TicketApproval $ticketApproval)
    {
        return $user->id == $ticketApproval->creator_id || ($ticketApproval->ticket->technician && $user->id == $ticketApproval->ticket->technician->id);
    }

    public function approval_show(User $user, TicketApproval $ticketApproval)
    {
        return $ticketApproval->pending && $ticketApproval->approver_id == $user->id && !$ticketApproval->ticket->isClosed();
    }

    public function approval_resend(User $user, TicketApproval $ticketApproval)
    {
        return $ticketApproval->shouldSend() && $ticketApproval->pending && $user->id == $ticketApproval->creator_id;
    }

    public function approval_delete(User $user, TicketApproval $ticketApproval)
    {
        return $ticketApproval->pending && can('delete', $ticketApproval);
    }

}
