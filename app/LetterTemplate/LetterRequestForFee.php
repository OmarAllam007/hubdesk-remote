<?php


namespace App\LetterTemplate;


use App\TicketReply;

class LetterRequestForFee
{

    public function __construct()
    {

    }

    static function getReplyTemplate(): TicketReply
    {
        return new TicketReply([

        ]);
    }
}