@component('mail::message')
# Reminder on New Created Ticket #{{$ticket->id}}

<div style="font-size: 13px; font-family: 'Helvetica Neue', Helvetica, Arial,sans-serif">
    Ticket ID: #{{link_to_route('ticket.show', $ticket->id,$ticket->id)}}<br/>
    Requested by: {{$ticket->created_by->name}}<br/>
    Requested at: {{$ticket->created_at->format('d/m/Y H:i')}}<br/>
    Content:<br/><br />

    <div>
        {!! $ticket->description !!}
    </div><br/><br />
</div>
<div style="padding-left: 15px;">
    @component('mail::button', ['url' => route('ticket.show',$ticket->id)])
        <b class="center-block">Click here to View</b>
    @endcomponent
        <div class="alert alert-info" role="alert">
            <p  style="background-color:#f6f7d2; border-radius: 5px;font-size: small; padding: 10px;margin: 10px;text-align: center">
                {{t('Please don\'t reply on this email and give the approval through the system')}}
            </p>
        </div>
</div>


@endcomponent
