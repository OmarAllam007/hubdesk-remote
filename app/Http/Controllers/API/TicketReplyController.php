<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketApprovalResource;
use App\Http\Resources\TicketReplyResource;
use App\Status;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;

class TicketReplyController extends Controller
{
    function index(Ticket $ticket)
    {
        return [
            'replies' => TicketReplyResource::collection($ticket->replies()->latest()->get()),
            'approvals' => TicketApprovalResource::collection($ticket->approvals()->latest()->get()),
            'approvers' => User::active()->orderBy('name')->whereNotNull('email')->get(['name', 'id', 'email'])->map(function ($user) {
                return ['id' => $user->id, 'text' => $user->name . ' ( ' . $user->email . ' ) '];
            }),
            'statuses' => t(\App\Status::reply($ticket)->pluck('name', 'id')->prepend('Select Status', 0)),
            'templates' => auth()->user()->reply_templates
        ];
    }
}
