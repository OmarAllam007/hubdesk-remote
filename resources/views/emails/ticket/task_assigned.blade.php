@component('mail::message')
# A new task #{{$ticket->id}} been created for you on Ticket #{{$ticket->ticket->id}}.

<div style="font-size: 13px; font-family: 'Helvetica Neue', Helvetica, Arial,sans-serif">
Subject: <strong>{{$ticket->subject}}</strong><br>
At: {{$ticket->created_at->format('d/m/Y H:i')}}<br/>
Content: <br/><br/>

<div>
{!! $ticket->description !!}
</div>

<br><br>
</div>
<div style="padding-left: 15px;">
@component('mail::button', ['url' => route('ticket.show',$ticket->id)])
<b class="center-block">Display Task</b>
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
