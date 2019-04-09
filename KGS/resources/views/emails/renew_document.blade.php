@component('mail::message')
    # Request for renew the document
The Document ( {{$document->name}} ) will expired in {{$document->end_date->format('d-m-Y')}}

@component('mail::button', ['url' => route('kgs.document.index',$document->business_unit)])
Display
@endcomponent

Thanks,

{{ config('app.name') }}
@endcomponent