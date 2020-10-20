<div>
    @php
        $notes = $ticket->notes()->get()->map(function ($note){
        return $note->convertToJson();
        });

        $can_create_note = \Auth::user()->isSupport() && !$ticket->isTask() ? 1 : 0;
    @endphp
    <notes :notes="{{$notes}}"
           :can_create_note="{{$can_create_note}}"
           :ticket_id="{{$ticket->id}}"
    ></notes>
</div>