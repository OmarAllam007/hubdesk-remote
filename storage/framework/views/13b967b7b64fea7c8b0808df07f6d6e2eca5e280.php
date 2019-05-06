<?php $__env->startSection('body'); ?>

    <?php if(can('show_survey',$ticket)): ?>
        <?php if($survey->submittedBefore($ticket)): ?>
            <div class="container text-center">
                <div class="alert alert-info alert-dismissible text-center" role="alert">
                    <h4><strong><?php echo e(t('Your survey information for this request has already been received for consideration')); ?>.
                            <a href="<?php echo e(route('ticket.show',$ticket)); ?>"><?php echo e(t('Return to the ticket')); ?></a></strong></h4>
                </div>
            </div>
        <?php else: ?>
            <form action="<?php echo e(route('ticket.survey',[$ticket,$survey])); ?>" method="post">
                <?php echo e(csrf_field()); ?>

                <div class="container">
                    <h4><b><?php echo e(t('Welcome')); ?> <?php echo e($ticket->requester->name); ?></b>,</h4>
                    <h4><?php echo e(t('Survey sent for request')); ?> " <?php echo e($ticket->subject); ?> "</h4>

                    <h5><b><?php echo e(t('Request ID')); ?></b>: <?php echo e($ticket->id); ?> |
                        <b><?php echo e(t('Created On')); ?></b>: <?php echo e($ticket->created_at ? $ticket->created_at->format('d-M-Y h:m a') : ''); ?> |
                        <b><?php echo e(t('Closed On')); ?> </b>: <?php echo e($ticket->close_date ? $ticket->close_date->format('d-M-Y h:m a') : ''); ?>

                    </h5>
                    <br>
                    <h4><b><?php echo e(t('Please help us to improve our service by participating in this brief survey')); ?>.</b></h4>
                    <hr>
                    <?php if($ticket->category->survey->first()): ?>
                        <?php if($ticket->category->survey->first()->questions->count()): ?>
                            <?php $__currentLoopData = $ticket->category->survey->first()->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="panel panel-primary ">
                                    <div class="panel-heading ">
                                        <h4><?php echo e($question->description); ?></h4>
                                    </div>
                                    <div class="panel-body">
                                        <?php $__currentLoopData = $question->answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="questions[<?php echo e($question->id); ?>]"
                                                           id="optionsRadios1"
                                                           value="<?php echo e($answer->id); ?>">
                                                    <?php echo e($answer->description); ?>

                                                </label>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <hr>
                    <br>
                    <h4><b><?php echo e(t('Your suggestions will help us improve our service, kindly let us know if any')); ?></b></h4>

                    <div class="form-group">
                        <textarea class="from-control" name="comment" rows="10" cols="100"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success"><?php echo e(t('Submit')); ?></button>
                </div>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <div class="container text-center">
            <div class="alert alert-danger alert-dismissible text-center" role="alert">
                <h4><strong><?php echo e(t('You are not authorized to view this page')); ?>.</strong></h4>
            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>