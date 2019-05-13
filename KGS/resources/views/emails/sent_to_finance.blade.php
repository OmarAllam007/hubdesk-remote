@component('mail::message')
#Hubdesk-Finance -  Request #{{$ticket->id}}
<br>
<p><strong>Main Service Subject :</strong> {{$ticket->subject}}</p>
<p><strong>Comments :</strong> {!! $content !!}</p>

<p><strong>Cost Details:</strong></p>

@component('mail::table')
| **Service**   | **Cost**  |
|:-----------   |:--------: |
| {{$ticket->subject}}  | {{ number_format($ticket->total_service_cost,1) ?? 0 }}|
@foreach($ticket->tasks as $key=>$task)
| {{$task->subject_label}} | {{ number_format($task->total_service_cost,1)}} |
@endforeach
| **Total Cost **   |  {{ number_format($ticket->total_ticket_cost,1) }}|
@endcomponent

@component('mail::button', ['url' => route('ticket.show',$ticket)])
    Display
@endcomponent

Thanks,

{{ config('app.name') }}
@endcomponent