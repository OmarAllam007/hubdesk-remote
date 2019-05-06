<ul class="list-group panel-design ticket-description">
    <li class="list-group-item created-log">
        <?php if(!$ticket->isTask()): ?>
            <strong><?php echo e(t('Ticket created by')); ?>  <?php echo e($ticket->created_by->name); ?> <?php echo e(t('at')); ?> <?php echo e($ticket->created_at->format('d/m/Y H:i')); ?></strong>
        <?php else: ?>
            <strong><?php echo e(t('Task created by')); ?>  <?php echo e($ticket->created_by->name); ?> <?php echo e(t('at')); ?> <?php echo e($ticket->created_at->format('d/m/Y H:i')); ?></strong>
        <?php endif; ?>
    </li>
    <?php $__currentLoopData = $ticket->logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li class="list-group-item <?php echo e($log->color_type); ?>">
            <?php if($log->type == \App\TicketLog::AUTO_CLOSE): ?>
                <strong><?php echo e(t('Ticket has been closed by the system')); ?></strong>
            <?php elseif($log->type == \App\TicketLog::ESCALATION): ?>
                <strong><?php echo e(T('Ticket has been Escalated to')); ?> <?php echo e($log->user->name); ?></strong>
            <?php else: ?>
                <strong><?php echo e(t($ticket->isTask() ? 'Task '.$log->type_action.' by' :'Ticket '.$log->type_action.' by')); ?>   <?php echo e($log->user->name); ?>

                    <?php echo e(t('at')); ?> <?php echo e($log->created_at->format('d/m/Y H:i')); ?></strong>
                <ul>
                    <?php $__currentLoopData = $log->entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <small><?php echo e(t($entry->label.' changed from')); ?>  <strong><?php echo e(t($entry->old_value) ?: 'None'); ?></strong>
                                <?php echo e(t('to')); ?> <strong><?php echo e(t($entry->new_value) ?: 'None'); ?></strong></small>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>

        </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>