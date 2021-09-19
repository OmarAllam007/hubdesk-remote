<?php

namespace Tests\Unit;

use App\Ticket;
use Tests\TestCase;


class LetterTaskTest extends TestCase
{
    /** @test */
    public function update_letter_ticket_task()
    {
        /** @var Ticket $ticket */
        $ticket = Ticket::find(1440005);

        $ticket->update([
            'status_id' => 7
        ]);
    }
}
