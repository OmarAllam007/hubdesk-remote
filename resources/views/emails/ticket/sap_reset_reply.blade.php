@component('mail::message')
# The ticket #{{$ticket['id']}} has a new reply.

<div style="font-size: 13px; font-family: 'Helvetica Neue', Helvetica, Arial,sans-serif">
Ticket ID: #{{link_to_route('ticket.show', $ticket['id'], $ticket['id'])}}<br/>
At: {{$reply->created_at->format('d/m/Y H:i')}}<br/>
Content:<br/><br />
<div>
Your Password has been reset for {{$field}}, kindly check your ticket.
</div>

<br><br>
</div>
<div style="padding-left: 15px;">
@component('mail::button', ['url' => route('ticket.show',$ticket['id'])])
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
