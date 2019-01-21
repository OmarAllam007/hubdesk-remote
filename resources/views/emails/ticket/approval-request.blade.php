<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approval request</title>
</head>
<body>
    <div style="font-size: 13px; font-family: 'Helvetica Neue', Helvetica, Arial,sans-serif">
        Ticket ID: #{{link_to_route('ticket.show', $approval->ticket->id, $approval->ticket->id)}}<br/>
        Requested by: {{$approval->created_by->name}}<br/>
        Requested at: {{$approval->created_at->format('d/m/Y H:i')}}<br/>
        Content:<br/><br />

        <div>
            {!! $content !!}
        </div>

        <br><br>
        Approval link: <button class="btn btn-primary" href="{{route('approval.show',$approval->id)}}"> Approval Link</button> <br/>
    </div>
</body>
</html>