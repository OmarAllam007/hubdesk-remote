<div class="panel panel-default panel-design">
    <div class="panel-heading">
        <h4 class="panel-title"><i class="fa fa-user"></i> <?php echo e(t('Requester Details')); ?></h4>
    </div>
    <table class="table table-striped table-condensed details-tbl">
        <tr>
            <th class="col-sm-3"><?php echo e(t('Name')); ?></th>
            <td class="col-sm-3"><?php echo e($ticket->requester->name); ?></td>
            <th class="col-sm-3"><?php echo e(t('Business Unit')); ?></th>
            <td class="col-sm-3"><?php echo e(isset($ticket->requester->business_unit->name) ? $ticket->requester->business_unit->name : 'Not Assigned'); ?></td>
        </tr>
        <tr>
            <th><?php echo e(t('Department')); ?></th>
            <td><?php echo e(isset($ticket->requester->department->name) ? $ticket->requester->department->name : 'Not Assigned'); ?></td>
            <th><?php echo e(t('Job Title')); ?></th>
            <td><?php echo e(isset($ticket->requester->job) ? $ticket->requester->job : 'Not Assigned'); ?></td>
        </tr>
        <tr>
            <th><?php echo e(t('Email')); ?></th>
            <td><?php echo e(isset($ticket->requester->email) ? $ticket->requester->email : 'Not Assigned'); ?></td>
            <th><?php echo e(t('Employee ID')); ?></th>
            <td><?php echo e(isset($ticket->requester->employee_id) ? $ticket->requester->employee_id : 'Not Assigned'); ?></td>
        </tr>
        <tr>
            <th><?php echo e(t('Phone')); ?></th>
            <td><?php echo e(isset($ticket->requester->phone) ? $ticket->requester->phone : 'Not Assigned'); ?></td>
            <th><?php echo e(t('Mobile')); ?></th>
            <td><?php echo e(isset($ticket->requester->mobile) ? $ticket->requester->mobile : 'Not Assigned'); ?></td>
        </tr>


    </table>
</div>