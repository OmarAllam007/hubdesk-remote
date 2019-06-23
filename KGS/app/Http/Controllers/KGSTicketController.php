<?php
/**
 * Created by PhpStorm.
 * User: omarkhaled
 * Date: 2019-03-10
 * Time: 10:55
 */

namespace KGS\Http\Controllers;


use App\Attachment;
use App\BusinessUnit;
use App\Category;
use App\Http\Controllers\Controller;
use App\Item;
use App\Subcategory;
use App\Task;
use App\Ticket;
use App\TicketLog;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use KGS\DocumentNotification;
use KGS\Mail\SendFinanceMail;
use KGS\Requirement;
use Predis\Response\Status;

class KGSTicketController extends Controller
{
    function sendToFinance(Ticket $ticket,Request $request)
    {
        Ticket::flushEventListeners();

        $this->validate($request,['to'=>'required']);
        $to = User::whereIn('id',$request->to)->pluck('email')->toArray();

        $ticket->update(['status_id'=>10]);
        $data = ['ticket'=>$ticket,'content'=>$request->finance_content];

        \Mail::to($to)->send(new SendFinanceMail($data));

        TicketLog::makeLog($ticket,TicketLog::SENT_TO_FINANCE,\Auth::id());

        return redirect()->back();
    }
}