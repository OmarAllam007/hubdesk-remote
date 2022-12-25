<?php

namespace App\Listeners;

use App\CustomField;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TicketFieldsListener
{
    protected $request;

    public function __construct()
    {
        $this->request = request();
    }


    public function handle($ticket)
    {
        $items = json_decode($this->request->input('ticket.fields'), true);
        $this->createFields($items, $ticket);
    }

    public function createFields($items, $ticket): void
    {
        if ($items && count($items)) {
            foreach ($items as $key => $item) {
                if ($item) {
                    if (is_numeric($key)) {
                        $field = CustomField::find($key)->name ?? '';
                    } else {
                        $field = $key;
                    }
                    $ticket->fields()->create(['name' => $field, 'value' => $item]);
                }
            }
        }
    }
}
