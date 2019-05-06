<?php if($ticket->fields->count()): ?>
<div class="panel panel-default panel-design">
    <div class="panel-heading">
        <h4 class="panel-title"><i class="fa fa-asterisk"></i> <?php echo e(t('Additional Information')); ?></h4>
    </div>

    <table class="table table-bordered table-condensed table-striped details-tbl">
        <tbody>
        <?php $__currentLoopData = $ticket->fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="col-sm-4 text-right"><strong><?php echo e($field->name); ?></strong></td>
                <td>
                    <?php echo e($field->value); ?>

                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php endif; ?>