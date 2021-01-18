<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketReplyResource;
use App\Ticket;
use Illuminate\Http\Request;

class TicketReplyController extends Controller
{
    function index(Ticket $ticket)
    {
        return [
            'replies' => TicketReplyResource::collection($ticket->replies()->latest()->get()),
        ];
    }
}
