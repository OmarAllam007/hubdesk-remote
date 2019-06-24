@component('mail::message')
    # This is an escalation notification for a request getting non-satisfied survey.
    Requester : {{$survey->ticket->requester->name}}
    Category : {{$survey->ticket->category->name}}
    Title : {{$survey->ticket->subject}}
<div style="padding-left: 15px;">
    @component('mail::button', ['url' => route('user_survey.show',['survey' => $survey->id])])
        <b class="center-block">Display</b>
    @endcomponent
    <br><br>
    <div class="alert alert-info" role="alert">
        <p  style="background-color:#f6f7d2; border-radius: 5px;font-size: small; padding: 10px;margin: 10px;text-align: center">
            {{t('Please don\'t reply on this email')}}
        </p>
    </div>
</div>
@endcomponent