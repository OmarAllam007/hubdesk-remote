@component('mail::message')
# Request for Approval

<div style="font-size: 13px; font-family: 'Helvetica Neue', Helvetica, Arial,sans-serif">
    Ticket ID: #{{link_to_route('ticket.show', $approval->ticket->id, $approval->ticket->id)}}<br/>
    Requested by: {{$approval->created_by->name}}<br/>
    Requested at: {{$approval->created_at->format('d/m/Y H:i')}}<br/>
    Content:<br/><br />

    <div>
        {!! $content !!}
    </div>

    <br><br>
</div>

@component('mail::button', ['url' => route('approval.show',$approval->id)])
Approval Link
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
