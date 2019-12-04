@component('mail::message')
    # Request to renew the document

<br>
<p><strong>Document Name :</strong> {{$document->name}}</p>
<p><strong>Document End date :</strong> {{$document->end_date->format('d-m-Y')}}</p>
@component('mail::button', ['url' => route('kgs.document.index',$document->folder)])
Display
@endcomponent

Thanks,

{{ config('app.name') }}
@endcomponent