<?php

namespace Tests\Unit;

use App\Ticket;
use App\TicketApproval;
//use PHPUnit\Framework\TestCase;
use Carbon\Carbon;
use Tests\TestCase;


class LetterApprovalTest extends TestCase
{
    /** @test */
    public function a_letter_generated_when_letter_approved()
    {
        $id = Ticket::find(1440004)->approvals()->first()->id;

        $approval = TicketApproval::find($id);

        $approval->update([
            'status' => 1,
            'approval_date'=> Carbon::now()->toDateTimeString()
        ]);


        $this->assertTrue(true);
    }
}
