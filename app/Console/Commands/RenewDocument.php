<?php

namespace App\Console\Commands;

use App\Mail\DocumentReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use KGS\Document;
use Illuminate\Mail\Message;
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
        $documents = Document::where('end_date', '<=', Carbon::now()->addMonth(2)->toDateString())
            ->orWhere('end_date', '<=', Carbon::now()->addMonth(2)->subDays(3)->toDateString())
            ->get();

        foreach ($documents as $document) {
            $this->sendEmailNotification($document);
        }
    }

    function sendEmailNotification($document){
        \Mail::to($document->last_updated->email)->send(new DocumentReminder($document));
//        \Mail::send('kgs::emails.renew_document', ['document' => $document], function(Message $msg) use ($document) {
//            $msg->subject('A reminder to renew the document #' . $document->name);
//            $msg->to($document->last_updated->email);
//        });
    }
}
