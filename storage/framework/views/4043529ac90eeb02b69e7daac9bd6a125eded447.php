<div class="panel panel-default ticket-description">
    <div class="panel-body ">
        <?php echo tidy_repair_string($ticket->description, [], 'utf8'); ?>

    </div>
</div>

<div class="panel panel-default panel-design">
    <div class="panel-heading">
        <h4 class="panel-title"><i class="fa fa-ticket"></i>
            <?php if(!$ticket->isTask()): ?>
                <?php echo e(t('Request Details')); ?>

            <?php else: ?>
                <?php echo e(t('Task Details')); ?>

            <?php endif; ?>
        </h4>
    </div>
    <table class="table table-striped table-condensed details-tbl">
        <tr>
            <th class="col-sm-3"><?php echo e(t('Category')); ?></th>
            <td class="col-sm-3"><?php echo e(isset($ticket->category->name) ? $ticket->category->name : 'Not Assigned'); ?></td>
            <th class="col-sm-3"><?php echo e(t('Service Cost: ')); ?></th>
            <td class="col-sm-3"><?php echo e($ticket->total_ticket_cost ? t($ticket->total_ticket_cost . ' SAR') : 'Not Assigned'); ?></td>

        </tr> 
        <tr>
            <th class="col-sm-3"><?php echo e(t('Subcategory')); ?></th>
            <td class="col-sm-3"><?php echo e(isset($ticket->subcategory->name) ? $ticket->subcategory->name : 'Not Assigned'); ?></td>
            <th class="col-sm-3"><?php echo e(t('Technician')); ?></th>
            <td class="col-sm-3"><?php echo e(isset($ticket->technician->name) ? $ticket->technician->name : 'Not Assigned'); ?></td>
        </tr>
        <tr>
            <th class="col-sm-3"><?php echo e(t('Item')); ?></th>
            <td class="col-sm-3"><?php echo e(isset($ticket->Item->name) ? $ticket->Item->name : 'Not Assigned'); ?></td>
            <th class="col-sm-3"><?php echo e(t('First Response Due Time')); ?></th>
            <td class="col-sm-3"><?php echo e(isset($ticket->first_response_date) ? $ticket->first_response_date : 'Not Assigned'); ?></td>


        </tr>
        <tr>
            <th class="col-sm-3"><?php echo e(t('Due Time')); ?></th>
            <td class="col-sm-3"><?php echo e(isset($ticket->due_date) ? $ticket->due_date : 'Not Assigned'); ?></td>

            <th class="col-sm-3"><?php echo e(t('Urgency')); ?></th>
            <td class="col-sm-3"><?php echo e(isset($ticket->urgency->name) ? $ticket->urgency->name : 'Not Assigned'); ?></td>
        </tr>
        <tr>
            <th class="col-sm-3"><?php echo e(t('SLA')); ?></th>
            <td class="col-sm-3"><?php echo e(isset($ticket->sla->name) ? $ticket->sla->name : 'Not Assigned'); ?></td>
            <th class="col-sm-3"><?php echo e(t('Group')); ?></th>
            <td class="col-sm-3"><?php echo e(isset($ticket->group->name) ? $ticket->group->name : 'Not Assigned'); ?></td>
        </tr>
        
            
            
            
            
        
    </table>
</div>


<?php if($ticket->fees->count()): ?>
    <div class="panel panel-default panel-design">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-asterisk"></i> <?php echo e(t('Additional Fees')); ?></h4>
        </div>

        <table class="table table-bordered table-condensed table-striped details-tbl">
            <tbody>
            <?php $__currentLoopData = $ticket->fees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="col-sm-4 text-right"><strong><?php echo e($fee->name); ?></strong></td>
                    <td>
                        <?php echo e($fee->cost); ?>

                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php echo $__env->make('ticket.partials._ticket_additional_fields',['ticket'=>$ticket], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('ticket.partials._requester_details',['ticket'=>$ticket], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php if($ticket->notes->count()): ?>
    <div class="panel panel-default panel-design">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-sticky-note-o"></i> <?php echo e(t('Discussion Notes')); ?></h4>
        </div>
        <table class="table table-striped table-condensed details-tbl">
            <thead>
            <tr>
                <th><?php echo e(t('Created By')); ?></th>
                <th><?php echo e(t('Note')); ?></th>
                <th><?php echo e(t('Created at')); ?></th>
                <th><?php echo e(t('Actions')); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $ticket->notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($note->creator->name); ?></td>
                    <td><?php if($note->display_to_requester || Auth::user()->isSupport()): ?> <?php echo $note->note; ?> <?php else: ?> <b>private</b> <?php endif; ?>
                    </td>
                    <td><?php echo e($note->created_at->format('d/m/Y H:i A')); ?></td>
                    <td>
                        <button type="button" id="editNote" data-note="<?php echo e($note); ?>"
                                class="btn btn-primary btn-xs editNote" data-toggle="modal"
                                data-target="#ReplyModal">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button type="button" id="removeNote" data-note="<?php echo e($note); ?>"
                                class="btn btn-danger btn-xs removeNote" data-toggle="modal"
                                data-target="#removeNoteModal">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php $__env->startSection('javascript'); ?>
    <script>
        let ticket = <?php echo json_encode($ticket); ?>

    </script>
    <script src="<?php echo e(asset('/js/ticket-note.js')); ?>"></script>
<?php $__env->appendSection(); ?>