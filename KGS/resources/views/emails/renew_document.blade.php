@component('mail::message')
    # New Ticket for renew document

<br>
<p><strong>Document Name :</strong> {{$document->name}}</p>
<p><strong>Document End date :</strong> {{$document->end_date->format('d-m-Y')}}</p>
<p><strong>Business Unit :</strong> {{$document->folder->business_unit->name ?? 'Not assigned'}}</p>
@component('mail::button', ['url' => route('ticket.show',$ticket->id)])
Display
@endcomponent

Thanks,

{{ config('app.name') }}
@endcomponent