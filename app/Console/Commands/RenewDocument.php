<?php

namespace App\Console\Commands;

use App\DocumentTicket;
use App\Mail\DocumentReminder;
use App\Ticket;
use App\TicketRequirements;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use KGS\Document;
use Illuminate\Mail\Message;
use KGS\DocumentNotification;
use KGS\KGSBusinessUnit;
use KGS\KGSLog;

class RenewDocument extends Command
{

    protected $signature = 'document:renew';

    protected $description = 'Notify stakeholders of the BU to renew the document';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
//        Ticket::flushEventListeners();
        $documents = Document::all();

        foreach ($documents as $document) {
            if ($document->id == 3) {// to be removed
                $notificationStartDate = $document->end_date->subDays($document->notify_duration ?? 0);
                $shouldNotify = $notificationStartDate->lessThanOrEqualTo(Carbon::now());

                if ($shouldNotify) {

                    if (!$this->hasOpenDocumentTicket($document)) {
                        $this->createTickets($document);
                    }

                }
            }

        }
    }

    private function hasOpenDocumentTicket($document)
    {
        return DocumentTicket::where('document_id', $document->id)
            ->whereHas('ticket', function ($q) {
                $q->whereNotIn('status_id', [7, 8, 9]);
            })->first();
    }

    function sendEmailNotification($document, $ticket)
    {
        $to = [6761, 1499501];//SAEED and EID Mabrok

        if (in_array($document->business_unit_id, [7])) {// special case for KRB abdulmohsen almugren
            $to[] = 1334;
        }

        $users = User::whereIn('id', $to)->get();

        if (!empty($users)) {
            \Mail::to($users)->send(new DocumentReminder($document, $ticket));
        }
    }

    private function createTickets($document)
    {
        $ticket = Ticket::create([
            'requester_id' => env('SYSTEM_USER'),
            'creator_id' => env('SYSTEM_USER'),
            'category_id' => 161,
            'status_id' => 1,
            'subject' => 'Renew Document -' . $document->name,
            'description' => 'Renew Document -' . $document->name,
        ]);


//                    @Todo make an observer to send notifications as per map
        DocumentTicket::create([
            'ticket_id' => $ticket->id,
            'document_id' => $document->id,
        ]);

        foreach ($document->requirements as $requirement) {
            TicketRequirements::create([
                'ticket_id' => $ticket->id,
                'requirement_id' => $requirement->id
            ]);
        }

        $this->sendEmailNotification($document, $ticket);
    }
}
