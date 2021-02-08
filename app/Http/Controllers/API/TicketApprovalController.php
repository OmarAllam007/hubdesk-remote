<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketApprovalResource;
use App\Http\Resources\TicketReplyResource;
use App\Ticket;
use Illuminate\Http\Request;

class TicketApprovalController extends Controller
{
    function index(Ticket $ticket)
    {
        return [
        ];
    }
}
