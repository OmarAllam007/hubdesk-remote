<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Please help us to improve our service by participating in this brief survey.</title>
</head>
<body>
<div style="font-size: 13px; font-face: arial,helvetica,sans-serif">
    <font family="arial,helvetica,sans-serif">
        Dear <?php echo e($ticket->requester->name); ?>, Please help us improve our service by completing this short survey.<br>
        Your feedbacks and comments will help us to improve our service. We appreciate your time here.<br>
        Thanks and regards,
    </font>

    <br/><br/>
    To view survey details please go to <?php echo e(link_to_route('ticket.submitSurvey',null, [$ticket->id,$ticket->category->survey->first()->id])); ?>

</div>
</body>
</html>
