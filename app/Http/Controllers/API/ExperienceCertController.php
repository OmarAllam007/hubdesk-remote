<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ExperienceCertController extends Controller
{
    function store(Request $request){
        /** @var UploadedFile $file */

        $requestedTicket = $request->get('ticket');
//        return response()->json(['errors' => $requestedTicket, 'error_code' => 400]);
        $user = User::find($requestedTicket['requester_id']);
        $items = json_decode($request->input('ticket.fields'), true);




        $controller = new TicketController();
        $fieldValidation =  $controller->validateFields($items);

        if (count($fieldValidation)) {
            return response()->json(['errors' => $fieldValidation, 'error_code' => 400]);
        }

        if ($controller->validateFiles($request) > 10) {
            return response()->json(['file_size_error' => ['Attachments size should not exceed 10MB'], 'error_code' => 402]);
        }


        $validator = \Validator::make($request->all(), ['ticket.subject' => 'required', 'ticket.priority_id' => 'required'], [
            'ticket.priority_id.required' => 'Priority field is required.',
            'ticket.subject.required' => 'Subject field is required.'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'error_code' => 400]);
        }

        $ticket = $controller->createTicket($request,$requestedTicket,\Auth::id());

        $controller->createFields($items, $ticket);
        $controller->createFields( [9 => $user->employee_id ?? ""], $ticket);

        return response($ticket->id);
    }
}
