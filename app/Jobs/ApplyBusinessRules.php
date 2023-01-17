<?php

namespace App\Jobs;

use App\BusinessRule;
use App\Criteria;
use App\Mail\TicketReplyMail;
use App\Notifications\TicketAssigned;
use App\Ticket;
use App\TicketReply;
use App\User;

class ApplyBusinessRules extends MatchCriteria
{
    public $business_rule;
    public $ccEmails;
    /**
     * @var bool
     */
    private $applyActions;

    public function __construct(Ticket $ticket, $applyActions = true)
    {
        $this->ticket = $ticket;
        $this->ccEmails = collect();
        $this->applyActions = $applyActions;
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

        if ($this->ticket->technician) {
            $this->ticket->technician->notify(new TicketAssigned($this->ticket));
        }
    }

    public function fetchBusinessRule()
    {
        $rules = BusinessRule::with('criterions')->with('rules')->get();

        foreach ($rules as $rule) {
            if ($this->match($rule)) {
                $this->business_rule = $rule;
                $this->applyRule($rule);
            }
        }
    }

    private function applyRule($rule)
    {
        foreach ($rule->rules as $action) {
            if ($action['field'] == 'cc') {
                $this->ccEmails->push(User::find($action['value']));
            } else {
                if ($this->applyActions) {
                    $this->ticket->setAttribute($action->field, $action->value);
                }
            }
        }

        \Log::info("Applied business rule [id: $rule->id, name: $rule->name, ticket: {$this->ticket->id}]");
    }
}
