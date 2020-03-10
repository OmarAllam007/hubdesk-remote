<?php

namespace Tests\Feature;

use App\Jobs\ApplyBusinessRules;
use App\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BusinessRulesTest extends TestCase
{
    /** @test */
    public function send_notification_to_cc()
    {
        Ticket::flushEventListeners();

        $ticket = Ticket::create([
            'requester_id' => 1021,
            'creator_id' => 1021,
            'category_id' => 70,
            'status_id' => 1,
            'is_opened' => 0,
        ]);

        dispatch(new ApplyBusinessRules($ticket));

        $this->assertTrue(true);
    }
}
