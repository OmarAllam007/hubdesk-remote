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
}
