<?php

namespace App;


/**
 * App\TicketReplies
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $ticket_id
 * @property string $content
 * @property integer $status_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property Ticket $ticket
 * @property Status $status
 * @method static \Illuminate\Database\Query\Builder|\App\TicketReply whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TicketReply whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TicketReply whereTicketId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TicketReply whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TicketReply whereStatusId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TicketReply whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TicketReply whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TicketReply extends KModel
{
    protected $fillable = ['content', 'status_id', 'user_id', 'sdp_id','ticket_id','cc','to','created_at','updated_at'];


    protected $casts = ['cc' => 'array', 'to' => 'array'];

    const AUTO_REPLY = 'SYSTEM AUTO REPLY';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function getClassAttribute()
    {
        if ($this->status_id == 7) {
            return 'bg-green-600';
        } elseif ($this->user_id == $this->ticket->requester_id) {
            return 'bg-orange-600';
        } elseif ($this->user_id == $this->ticket->technician_id) {
            return 'bg-blue-600';
        } else {
            return 'bg-gray-600';
        }
    }

    function getImagesAttribute()
    {
        $images = Attachment::where('reference',$this->id)
            ->where('type',Attachment::TICKET_REPLY_TYPE)->get();

        return $images;
    }

    function getAttachmentsAttribute(){
        return Attachment::where('type',Attachment::TICKET_REPLY_TYPE)->where('reference',$this->id)->get();
    }
}
