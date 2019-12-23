<?php

namespace App\Console\Commands;

use App\Jobs\TicketAutoClosedJob;
use App\Mail\SendSurveyEmail;
use App\UserSurvey;
use App\Ticket;
use App\TicketLog;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoCloseResolvedTickets extends Command
{
    protected $signature = 'tickets:auto-close';

    protected $description = 'Auto close resolved tickets after 3 business days';

    /**
     * @var Carbon
     */
    protected $now;


    public function __construct()
    {
        parent::__construct();
        Carbon::setWeekendDays([Carbon::FRIDAY, Carbon::SATURDAY]);
        $this->now = Carbon::now();
    }

    public function handle()
    {
        Ticket::flushEventListeners();
        $tickets = Ticket::whereIn('status_id', [7, 9])->whereNull('type')->get();
        /** @var Ticket $ticket */

        foreach ($tickets as $ticket) {
            if ($this->shouldClose($ticket)) {
                $ticket->status_id = 8;
                $ticket->close_date = Carbon::now();
                $ticket->save();

                TicketLog::addAutoClose($ticket);
                dispatch(new TicketAutoClosedJob($ticket));

                if ($survey = $ticket->category->survey->first()) {
                    $this->sendSurvey($ticket, $survey);
                }
            }
        }


    }

    private function shouldClose(Ticket $ticket)
    {
        if ((!$ticket->resolve_date && $ticket->status_id == 9) || (!$ticket->resolve_date && $ticket->status_id == 7)) {
            $date = clone $ticket->updated_at; // for old not closed tickets
        } else {
            $date = clone $ticket->resolve_date;
        }

        for ($i = 0; $i < 3; ++$i) {
            $date->addDay();
            if ($date->isWeekend()) {
                --$i;
            }
        }

        return $this->now->gte($date);
    }

    private function sendSurvey($ticket, $survey)
    {


        $user_survey = UserSurvey::create([
            'ticket_id' => $ticket->id,
            'survey_id' => $survey->id,
            'comment' => '',
            'is_submitted' => 0,
            'notified' => 1
        ]);

        if($user_survey->ticket->requester->email){
            \Mail::send(new SendSurveyEmail($user_survey));
        }
    }
}
