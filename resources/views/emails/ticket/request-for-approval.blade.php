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
<div style="padding-left: 15px;">
    @component('mail::button', ['url' => route('approval.show',$approval->id)])
        <b class="center-block">Click here to take action</b>
    @endcomponent
        <div class="alert alert-info" role="alert">
            <p  style="background-color:#f6f7d2; border-radius: 5px;font-size: small; padding: 10px;margin: 10px;text-align: center">
                {{t('Please don\'t reply on this email and give the approval through the system')}}
            </p>
        </div>
</div>


@endcomponent
