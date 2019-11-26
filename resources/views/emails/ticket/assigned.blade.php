@component('mail::message')
# The ticket #{{$ticket->id}} has been assigned to you.

<div style="font-size: 13px; font-family: 'Helvetica Neue', Helvetica, Arial,sans-serif">
Ticket #{{$ticket->id}} has been assigned to you. <br /><br />

@if ($ticket->creator_id != $ticket->requester_id)
    By: {{$ticket->created_by->name}}<br/>
@endif
Requester: {{$ticket->requester->name}}<br/>
Subject: <strong>{{$ticket->subject}}</strong><br>
At: {{$ticket->created_at->format('d/m/Y H:i')}}<br/>
Status: {{$ticket->status->name}} <br />
Due Date: {{$ticket->due_date? $ticket->due_date->format('d/m/Y H:i') : 'N/A'}}<br/>
Content: <br/><br/>

<div>
{!! $ticket->description !!}
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
