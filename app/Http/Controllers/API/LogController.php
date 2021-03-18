<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketLogResource;
use App\Ticket;
use Illuminate\Http\Request;

class LogController extends Controller
{
    function index(Ticket $ticket)
    {
        $logsData = collect();
//        foreach ($ticket->history as $date=>$logs){
//            $logsData->put($date,TicketLogResource::make($this->logs));
//        }
//        $logsData = TicketLogResource::collection($ticket->logs);
//        dd($logsData);
//        $logsData->collection->groupBy('')
        return [
            'ticket_id' => $ticket->id,
            'created_by' => $ticket->created_by->name,
            'created_at' => $ticket->created_at->format('d/m/Y H:i'),
            'is_task' => $ticket->isTask() ? 1 : 0,
            'logs'=> TicketLogResource::collection($ticket->logs)

        ];
    }
}
