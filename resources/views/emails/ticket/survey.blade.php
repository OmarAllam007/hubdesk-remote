@component('mail::message')
    # Please help us to improve our service by participating in this brief survey.
<div style="font-size: 13px; font-family: 'Helvetica Neue', Helvetica, Arial,sans-serif">
    Dear {{$ticket->requester->name}}, Please help us improve our service by completing this short survey.<br><br>
    Your feedbacks and comments will help us to improve our service. We appreciate your time here.<br><br>
    Thanks and regards,
    <br><br>
</div>
<div style="padding-left: 15px;">
    @component('mail::button', ['url' => route('ticket.submitSurvey',[$ticket->id,$ticket->category->survey->first()->id])])
        <b class="center-block">Click here to take action</b>
    @endcomponent
    <br><br>
    <div class="alert alert-info" role="alert">
        <p  style="background-color:#f6f7d2; border-radius: 5px;font-size: small; padding: 10px;margin: 10px;text-align: center">
            {{t('Please don\'t reply on this email and give the approval through the system')}}
        </p>
    </div>
</div>
@endcomponent