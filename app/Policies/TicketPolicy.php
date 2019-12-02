<?php

namespace App\Policies;

use App\Ticket;
use App\TicketApproval;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;
    use TaskTrait;
    use TicketTrait;

    protected $map = [1 => 'ticket', 2 => 'task'];


    function __call($name, $args)
    {
        $ticket = $args[1];
        $prefix = $this->map[$ticket->type] ?? '';

        if (!$prefix) {
            return false;
        }

        $ability = $prefix . '_' . $name;
        if (method_exists($this, $ability)) {
            return $this->$ability(...$args);
        }

        return false;
    }

    function reply(User $user, Ticket $ticket)
    {
        $privileged = [$ticket->requester_id, $ticket->technician_id, $ticket->coordinator_id];

        return in_array($user->id, $privileged) ||
            $user->groups->contains($ticket->group_id) || $user->isTechnician();
    }

    function delete(User $user, Ticket $ticket)
    {
        return $user->id == $ticket->technician_id;
    }

    function resolve(User $user, Ticket $ticket)
    {
        return $user->id == $ticket->technician_id || $user->isTechnicainSupervisor($ticket);
    }

    function pick(User $user, Ticket $ticket)
    {
        if (($user->hasGroup($ticket->group) && $user->id != $ticket->technician_id)) {
            return true;
        }

        return false;
    }

    function show_approvals(User $user, Ticket $ticket)
    {
        return $user->isSupport();
    }


    public function modify(User $user, Ticket $task)
    {
        return in_array($user->id, [$task->technician_id, $task->creator_id, $task->requester_id]) || $user->isTechnicainSupervisor($task);
    }

    public function reassign(User $user, Ticket $ticket)
    {
        return $user->id == $ticket->technician_id || $user->isTechnicainSupervisor($ticket);
    }

    public function show(User $user, Ticket $ticket)
    {
        $isInCC = false;
        $isInToUsers = false;

        $isApprover = $ticket->approvals()->where('approver_id', $user->id)->exists();

        $isTaskTechnicianOrCreator = $ticket->tasks()->where(function ($q) use ($user) {
            $q->where('technician_id', $user->id)->orWhere('creator_id', $user->id);
        })->exists();
        $isTicketOwner = Ticket::where('id', $ticket->request_id)->where('technician_id', $user->id)->exists();

        if ($ticket->replies->pluck('cc')->count()) {
            $cc_users = User::whereIn('email', $ticket->replies->pluck('cc')->flatten()->filter()->toArray())->pluck('id')->toArray();
            $isInCC = in_array($user->id, $cc_users) ? true : false;
        }

        if ($ticket->replies->pluck('to')->count()) {
            $to_users = User::whereIn('email', $ticket->replies->pluck('to')->flatten()->filter()->toArray())->pluck('id')->toArray();
            $isInToUsers = in_array($user->id, $to_users) ? true : false;
        }

        return in_array($user->id, [$ticket->technician_id, $ticket->requester_id, $ticket->creator_id])
            || $user->hasGroup($ticket->group) || $isApprover || $isTaskTechnicianOrCreator || $isTicketOwner
            || $isInCC || $isInToUsers;
    }

    public function forward(User $user, Ticket $ticket)
    {
        return $user->id == $ticket->technician_id || $user->isTechnicainSupervisor($ticket);
    }

    function show_survey(User $user, Ticket $ticket)
    {
        return $user->id == $ticket->requester_id;
    }

    function reopen(User $user, Ticket $ticket)
    {
        return $user->isAdmin();
    }

    public function send_to_finance(User $user, Ticket $ticket)
    {
        $is_ticket_technician = $user->id == $ticket->technician_id;
        $is_valid_status = in_array($ticket->status_id, [7, 8, 9]);

        return $is_ticket_technician && $is_valid_status;
    }


    function submit_approval(User $user, Ticket $ticket)
    {
        return $user->id == $ticket->technician_id && !$ticket->isClosed();
    }
}
