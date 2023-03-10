<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketReplyRequest;
use App\Http\Resources\TicketApprovalResource;
use App\Http\Resources\TicketReplyResource;
use App\Jobs\TicketReplyJob;
use App\Mail\TicketAssignedMail;
use App\Mail\TicketReplyMail;
use App\Status;
use App\Ticket;
use App\TicketReply;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
            'templates' => auth()->user()->reply_templates,
            'ticket' => $ticket->convertToJson(),
            'show_approvals' => can('show_approvals', $ticket),
            'show_templates' => auth()->user()->isSupport() && auth()->user()->reply_templates->count(),
        ];
    }

    function store(Ticket $ticket, TicketReplyRequest $request)
    {
        $reply = new TicketReply($request->get('reply'));

        // KGS Request FOR KRB 🤦🏻 🤦🏻 🤦🏻‍️
        if (in_array($ticket->category_id, [161, 170, 171])) {
            if (in_array($reply->status_id, [7, 8, 9]) && !in_array($request->user()->id, [6761, 1499501])) {
                return response()->json(['error' => t('You are not authorize to resolve the ticket'), 401]);
            }
        }

        $reply->user_id = $request->user()->id;

        $emails = null;

        if (isset($request->get("reply")["cc"])) {
            $emails = User::whereIn('id', $request->get("reply")["cc"])->get()->pluck('email')->toArray();
        }

        $reply->cc = $emails;

        $replyAttachments = [];

        $files = $request->allFiles();
        $attachments = data_get($files, "reply.attachments");

        if ($attachments) {
            foreach ($attachments as $attachment) {
                array_push($replyAttachments, $attachment);
            }
        }

        $request->request->set('attachments', $replyAttachments);

        if ($ticket->status_id == 8 && $request->get('reply')['status_id'] != 8) {
            if (can('reopen', $ticket)) {
                $reply = $ticket->replies()->save($reply);
                return response()->json(['message' => t('Reply has been added'),
                    'reply' => TicketReplyResource::make($reply)], 200);
            } else {
                return response()->json(['error' => t('Can\'t reply after the ticket is closed'), 401]);
            }
        } else {
            // Fires creating event in \App\Providers\TicketReplyEventProvider
            $reply = $ticket->replies()->save($reply);
            return response()->json(['message' => t('Reply has been added'),
                'reply' => TicketReplyResource::make($reply)], 200);
        }
    }

    function resolve(Request $request, Ticket $ticket)
    {
        $replyAttachments = [];

        $files = $request->allFiles();
        $attachments = data_get($files, "reply.attachments");

        if ($attachments) {
            foreach ($attachments as $attachment) {
                array_push($replyAttachments, $attachment);
            }
        }

        $request->request->set('attachments', $replyAttachments);

        $data = [
            'content' => $request->get('reply')['content'],
            'status_id' => 7,
            'user_id' => $request->user()->id
        ];

        $reply = $ticket->replies()->create($data);
        $this->dispatch(new TicketReplyJob($reply));

        return response()->json(['message' => t('Reply has been added'),
            'reply' => TicketReplyResource::make($reply)], 200);
    }
//
//    private function checkIfTheReplyOfKRB($ticket, $request, $reply)
//    {
//        return
//             //SAEED AND EID
//            && in_array($reply->status_id, [7, 8, 9]);
//    }

}
