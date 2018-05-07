<?php

namespace App\Policies;

use App\User;
use App\TicketApproval;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketApprovalPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the ticketApproval.
     *
     * @param  \App\User  $user
     * @param  \App\TicketApproval  $ticketApproval
     * @return mixed
     */
    public function view(User $user, TicketApproval $ticketApproval)
    {
        //
    }

    /**
     * Determine whether the user can create ticketApprovals.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the ticketApproval.
     *
     * @param  \App\User  $user
     * @param  \App\TicketApproval  $ticketApproval
     * @return mixed
     */
    public function update(User $user, TicketApproval $ticketApproval)
    {
        //
    }

    /**
     * Determine whether the user can delete the ticketApproval.
     *
     * @param  \App\User  $user
     * @param  \App\TicketApproval  $ticketApproval
     * @return mixed
     */
    public function delete(User $user, TicketApproval $ticketApproval)
    {
       return $user->id == $ticketApproval->creator_id || $user->id == ($ticketApproval->ticket->technician && $ticketApproval->ticket->technician->id);
    }
}
