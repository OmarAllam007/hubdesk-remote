@component('mail::message')
# The ticket {{$ticketApproval->ticket->id}} has been {{strtolower(\App\TicketApproval::$statuses[$ticketApproval->status]) }} by {{$ticketApproval->approver->name}}
<div style="font-size: 13px; font-family: 'Helvetica Neue', Helvetica, Arial,sans-serif">
Request ID : {{$ticketApproval->ticket_id}}
@if ($ticketApproval->ticket->creator_id != $ticketApproval->ticket->requester_id)
By: {{$ticketApproval->ticket->created_by->name}}<br/>
@endif
Requester: {{$ticketApproval->ticket->requester->name}}<br/>
Subject: <strong>{{$ticketApproval->ticket->subject}}</strong><br>
At: {{$ticketApproval->ticket->created_at->format('d/m/Y H:i')}}<br/>
Status: {{$ticketApproval->approval_status}} <br />
Action Date: {{$ticketApproval->updated_at? $ticketApproval->updated_at->format('d/m/Y H:i') : 'N/A'}}<br/>
Content:
<br><br>
{!! $ticketApproval->comment !!}
</div>
<div style="padding-left: 15px;">
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