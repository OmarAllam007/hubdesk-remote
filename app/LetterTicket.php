<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LetterTicket extends Model
{
    use SoftDeletes;
    protected $fillable = ['ticket_id', 'group_id', 'subgroup_id', 'letter_id', 'need_coc_stamp','to_user_id'];

    function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    function group()
    {
        return $this->belongsTo(LetterGroup::class, 'group_id');

    }

    function subgroup()
    {
        return $this->belongsTo(LetterGroup::class, 'subgroup_id');
    }

    function letter()
    {
        return $this->belongsTo(Letter::class, 'letter_id');
    }

    function user(){
        return $this->belongsTo(User::class,'to_user_id');
    }

    static function isApprovedTicket($ticket)
    {
        $letterTicket = LetterTicket::where('ticket_id', $ticket->id)->first();

        if ($letterTicket) {
            $isApproved = $letterTicket->ticket->approvals->whereNotIn('status', [0, -1])->count();

            if ($isApproved) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Ticket $ticket
     * @return mixed
     */
    static function isKGSLetterTicket($ticket)
    {
        if ($ticket->request_id) {
            $ticket = $ticket->ticket;
        }

        return $ticket->is_letter_ticket && $ticket->tasks()->count() != 0;
    }

    static function isCompletedTicket($ticket)
    {
        return $ticket->tasks()->count() == 0 && self::isApprovedTicket($ticket);
    }

    static function isCompletedKGSTicket($ticket)
    {
        return self::isApprovedTicket($ticket) &&
            $ticket->tasks()->whereIn('status_id', [7, 8])->exists();
    }

    static function can_display_attachments($ticket)
    {
        return self::isCompletedKGSTicket($ticket) || self::isCompletedTicket($ticket);
    }

    function getLastApprovalDateAttribute()
    {
        $approval = $this->ticket->approvals()->where('status', 1)->get()->last();

        return $approval->approval_date->format('d/m/Y');
    }
}
