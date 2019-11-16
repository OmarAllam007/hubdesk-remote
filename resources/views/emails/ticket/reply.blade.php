<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Ticket #{{$reply->ticket_id}}</title>
</head>
<body>
<div style="font-size: 13px; font-face: arial,helvetica,sans-serif">
    <font family="arial,helvetica,sans-serif">
        Your ticket #{{$reply->ticket->id}} has a new reply. <br/><br/>

        By: {{$reply->user->name}}<br/>
        At: {{$reply->created_at->format('d/m/Y H:i')}}<br/>
        Status: {{$reply->status->name}} <br/>
        Due Date: {{$reply->ticket->due_date? $reply->ticket->due_date->format('d/m/Y H:i') : 'N/A'}}<br/>
        Content: <br/><br/>
    </font>
    <p> ==================================== </p>
    <div>
        {!! $reply->content !!}
    </div>
    <p> ==================================== </p>
    <br/><br/>
    To view ticket details please go to {{link_to_route('ticket.show', null, $reply->ticket_id)}}

    @if(in_array($reply->status->id,[7,9]))
        <div class="alert alert-info" role="alert">
            <p style="background-color:#f6f7d2; border-radius: 5px;font-size: small; padding: 10px;margin: 10px;text-align: center">
                {{t('Kindly close the ticket or it will be auto-closed after 3 days from now')}}
            </p>
        </div>
    @endif
</div>
</body>
</html>
