<?php

namespace App\Console\Commands;

use App\Mail\DocumentReminder;
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
        $documents = Document::all();

        foreach ($documents as $document) {
            $bus = DocumentNotification::all()->groupBy('business_unit_id');
            $levels = $bus->get($document->folder->business_unit->id);

            if (empty($levels)) {
                continue;
            }

            foreach ($levels as $key => $level) {

                if ($document->shouldNotified($level)) {
                    $this->sendEmailNotification($document, $level);

                    KGSLog::create([
                        'document_id' => $document->id,
                        'level_id' => $level->id,
                        'type' => KGSLog::NOTIFICATION_TYPE
                    ]);
                }

            }
        }
    }

    function sendEmailNotification($document, $level)
    {
        $users = User::whereIn('id', $level->users)->get();

        if (!empty($users)) {
            foreach ($users->pluck('email') as $email) {
                \Mail::to($email)->send(new DocumentReminder($document));
            }
        }
    }
}
