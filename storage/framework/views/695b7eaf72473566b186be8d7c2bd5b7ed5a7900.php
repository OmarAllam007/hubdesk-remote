<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Ticket #<?php echo e($reply->ticket_id); ?></title>
</head>
<body>
<div style="font-size: 13px; font-face: arial,helvetica,sans-serif">
    <font family="arial,helvetica,sans-serif">
        Your ticket #<?php echo e($reply->ticket->id); ?> has a new reply. <br/><br/>

            By: <?php echo e($reply->user->name); ?><br/>
            At: <?php echo e($reply->created_at->format('d/m/Y H:i')); ?><br/>
            Status: <?php echo e($reply->status->name); ?> <br />
            Due Date: <?php echo e($reply->ticket->due_date? $reply->ticket->due_date->format('d/m/Y H:i') : 'N/A'); ?><br/>
            Content: <br/><br/>
        </font>
        <p> ==================================== </p>
        <div>
            <?php echo $reply->content; ?>

        </div>
        <p> ==================================== </p>
        <br/><br/>
        To view ticket details please go to <?php echo e(link_to_route('ticket.show', null, $reply->ticket_id)); ?>

    </div>
</body>
</html>
