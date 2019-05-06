<div class="conversation-approvals">
    <?php $__currentLoopData = $ticket->approvals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $approval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="panel panel-sm panel-warning">
            <div class="panel-heading">
                <h5 class="panel-title"><a href="#approval<?php echo e($approval->id); ?>"
                                           data-toggle="collapse"><?php echo e(t('Approval Submitted By')); ?>

                        : <?php echo e($approval->created_by->name); ?>

                        On <?php echo e($approval->created_at->format('d/m/Y H:i A')); ?>

                        To <?php echo e($approval->approver->name); ?>

                    </a>
                </h5>
            </div>
            <div class="panel-body collapse" id="approval<?php echo e($approval->id); ?>">
                <div class="reply">
                    <?php echo tidy_repair_string($approval->content, [], 'utf8'); ?>

                </div>
                <br>
                <span class="label label-default">Status: <?php echo e(t(\App\TicketApproval::$statuses[$approval->status])); ?></span>

                <?php if($approval->attachments->count()): ?>
                    <br><br>
                    <p><strong>Attachments</strong></p>
                    <?php $__currentLoopData = $approval->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <ul class="list-unstyled">
                            <li><a href="<?php echo e($attachment->url); ?>" target="_blank"><?php echo e($attachment->display_name); ?></a>
                            </li>
                        </ul>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>