<?php


namespace App\Helpers;


use App\Attachment;
use App\LetterTicket;
use App\Ticket;

class GenerateLetter
{
    private $ticket;
    protected $user;
    protected $view;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    function get_user_information_from_sap()
    {
        $sapApi = new SapApi($this->ticket->requester);
        $this->user = $sapApi->getUserInformation();
    }

    function get_letter_view()
    {
        $letterTicket = LetterTicket::where('ticket_id', $this->ticket->id)->first();
        return  $letterTicket->letter->view_path;
    }

    function create_letter(){
        // in listener
        Attachment::uploadFiles(Attachment::TICKET_REPLY_TYPE, $reply->id);

    }

}