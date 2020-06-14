<?php

namespace App\Jobs;

use App\BusinessRule;
use App\Criteria;
use App\Mail\TicketReplyMail;
use App\Ticket;
use App\TicketReply;
use App\User;

class ApplyBusinessRules extends MatchCriteria
{
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function handle()
    {
        if (!$this->ticket->shouldApplyRules()) {
            return false;
        }

        $rules = BusinessRule::with('criterions')->with('rules')->get();

        foreach ($rules as $rule) {
            if ($this->match($rule)) {
                $this->applyRule($rule);

                if ($rule->is_last) {
                    break;
                }
            }
        }

        $this->ticket->stopLog(true)->setApplyRules(false);
        $this->ticket->save();
    }

    private function applyRule($rule)
    {
        $cc = collect();

        foreach ($rule->rules as $action) {

            if ($action['field'] == 'cc') {
                $cc->push(User::find($action['value'])->email);
            } else {
                $this->ticket->setAttribute($action->field, $action->value);
            }
        }
        if ($cc->count()) {
            TicketReply::flushEventListeners();

            $reply = $this->ticket->replies()->create([
                'user_id' => env('SYSTEM_USER') ?? $this->ticket->requester->id,
                'content' => TicketReply::AUTO_REPLY,
                'status_id' => $this->ticket->status_id,
                'cc' => $cc->toArray()
            ]);
            \Mail::to($cc)->cc($cc)->send(new TicketReplyMail($reply));
        }

        \Log::info("Applied business rule [id: $rule->id, name: $rule->name, ticket: {$this->ticket->id}]");
    }
}
