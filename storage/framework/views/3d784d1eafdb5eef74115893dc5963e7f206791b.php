<?php $__env->startComponent('mail::message'); ?>
    # Please help us to improve our service by participating in this brief survey.
<div style="font-size: 13px; font-family: 'Helvetica Neue', Helvetica, Arial,sans-serif">
    Dear <?php echo e($ticket->requester->name); ?>, Please help us improve our service by completing this short survey.<br><br>
    Your feedbacks and comments will help us to improve our service. We appreciate your time here.<br><br>
    Thanks and regards,
    <br><br>
</div>
<div style="padding-left: 15px;">
    <?php $__env->startComponent('mail::button', ['url' => route('ticket.submitSurvey',[$ticket->id,$ticket->category->survey->first()->id])]); ?>
        <b class="center-block">Click here to take action</b>
    <?php echo $__env->renderComponent(); ?>
    <br><br>
    <div class="alert alert-info" role="alert">
        <p  style="background-color:#f6f7d2; border-radius: 5px;font-size: small; padding: 10px;margin: 10px;text-align: center">
            <?php echo e(t('Please don\'t reply on this email and give the approval through the system')); ?>

        </p>
    </div>
</div>
<?php echo $__env->renderComponent(); ?>