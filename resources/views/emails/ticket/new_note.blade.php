@component('mail::message')
# The ticket #{{$note->ticket->id}} has been updated with new note.

<div style="font-size: 13px; font-family: 'Helvetica Neue', Helvetica, Arial,sans-serif">

By: {{$note->creator->name ?? 'N/A'}}<br />
At: {{$note->created_at->format('d/m/Y H:i A')}}<br/>
Note:

<div>
{!! $note->note !!}
</div>

<br><br>
</div>
<div style="padding-left: 15px;">

@component('mail::button', ['url' => route('ticket.show',$note->ticket->id)])
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
