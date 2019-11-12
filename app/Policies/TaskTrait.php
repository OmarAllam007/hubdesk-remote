<?php
/**
 * Created by PhpStorm.
 * User: omar
 * Date: 10/15/17
 * Time: 10:24 AM
 */

namespace App\Policies;


use App\Ticket;
use App\User;

trait TaskTrait
{
    public function task_read(User $user, Ticket $task)
    {
        return $user->id == $task->ticket->technician_id ||
            $user->groups->contains($task->ticket->group_id) ||
            $task->technician_id || $user->isTechnicainSupervisor($task->ticket);
    }

    public function task_show(User $user, Ticket $ticket)
    {
        return in_array($user->id,[$ticket->technician_id,$ticket->ticket->technician_id]) || $user->isTechnicainSupervisor($ticket);
    }

    public function task_create(User $user, Ticket $ticket)
    {
        return $user->id == $ticket->technician_id || $user->isTechnicainSupervisor($ticket);
    }

    function task_edit(User $user, Ticket $ticket)
    {
        return $user->id == $ticket->technician_id || $user->id == $ticket->ticket->technician_id;
    }

    function task_delete(User $user, Ticket $ticket)
    {
        return $user->id == $ticket->technician_id || $user->id == $ticket->ticket->technician_id;
    }

    public function task_close(User $user, Ticket $task)
    {
        return $user->id == $task->ticket->technician_id || $user->isTechnicainSupervisor($task->ticket);
    }

}