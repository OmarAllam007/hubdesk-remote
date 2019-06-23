<?php echo e(Form::model($ticket, ['route' => ['ticket.duplicate', $ticket], 'class' => 'modal fade', 'id' => 'DuplicateForm','method'=>'get'])); ?>

<?php echo e(csrf_field()); ?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo e(t('Duplicate Ticket')); ?> #<?php echo e($ticket->id); ?></h4>
        </div>

        <div class="modal-body" id="TicketForm">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo e(t('Are you sure to duplicate Ticket')); ?> #<?php echo e($ticket->id); ?>?
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="tickets_count"><?php echo e(t('Number of duplicated tickets')); ?> :</label>
                <input name="tickets_count" id="tickets_count" type="text" class="form-control" value="1">
            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-success"><i class="fa fa-copy"></i> <?php echo e(t('Duplicate')); ?></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(t('Close')); ?></button>
        </div>
    </div>
</div>
<?php echo e(Form::close()); ?>

