<?php

namespace App\Http\Controllers;

use App\Jobs\ApplySLA;
use App\Jobs\NewNoteJob;
use App\Ticket;
use App\TicketNote;
use Illuminate\Http\Request;

class TicketNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public function store(Ticket $ticket, Request $request)
    {
        $note = $ticket->notes()->create([
            'user_id' => $request->user()->id,
            'note' => $request->note,
            'display_to_requester' => $request->display_to_requester ? 1 : 0,
            'email_to_technician' => $request->email_to_technician ? 1 : 0,
            'as_first_response' => $request->as_first_response ? 1 : 0
        ]);

        if ($note->email_to_technician) {
            $this->dispatch(new NewNoteJob($note));
        }
        if ($note->as_first_response) {
            $this->dispatch(new ApplySLA($note->ticket));
        }
        /** @var TicketNote $note */
        return $note->convertToJson();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\TicketNote $ticketNote
     * @return \Illuminate\Http\Response
     */
    public function show(TicketNote $ticketNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\TicketNote $ticketNote
     * @return \Illuminate\Http\Response
     */
    public function edit(TicketNote $ticketNote)
    {
        //
    }


    public function update(Request $request, TicketNote $ticketNote)
    {
        $note = TicketNote::find($request->id);

        $note->note = $request->note;
        $note->display_to_requester = $request->display_to_requester ? 1 : 0;
        $note->email_to_technician = $request->email_to_technician ? 1 : 0;
        $note->as_first_response = $request->as_first_response ? 1 : 0;
        $note->save();

        if ($note->email_to_technician) {
            $this->dispatch(new NewNoteJob($note));
        }

        return $note;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\TicketNote $ticketNote
     * @return \Illuminate\Http\Response
     */
    public function destroy(TicketNote $note)
    {
        $note->delete();
    }
}
