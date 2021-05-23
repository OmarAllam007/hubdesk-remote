@component('mail::message')

<div style="font-size: 13px; font-family: 'Helvetica Neue', Helvetica, Arial,sans-serif">
Technician: {{$ticket->technician->name ?? 'N/A'}}<br />
Requester: {{$ticket->requester->name ?? 'N/A'}}<br />
Subject: <strong>{{$ticket->subject}}</strong><br />
At: {{$ticket->created_at->format('d/m/Y H:i')}}<br/>
Due Date: {{$ticket->due_date? $ticket->due_date->format('d/m/Y H:i') : 'N/A'}}<br/>
Content: <br/><br/>
<div>
{!! $ticket->description !!}
</div><br><br>
Type:<br><br>
<div>
    {{$complaint->type_str}}
</div>
<br><br>
</div>
<div style="padding-left: 15px;">
@component('mail::button', ['url' => route('ticket.show',$ticket->id)])
<b class="center-block">Display Ticket</b>
@endcomponent
<div class="alert alert-info" role="alert">
<p  style="background-color:#f6f7d2; border-radius: 5px;font-size: small; padding: 10px;margin: 10px;text-align: center">
    <strong>
        {{t('Please don\'t reply on this email')}}
    </strong>
</p>
</div>
</div>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
