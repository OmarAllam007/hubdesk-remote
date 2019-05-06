<?php echo e(Form::open(['route' => ['ticket.note', $ticket],'id'=>'noteFrom'])); ?>

<?php echo e(csrf_field()); ?>

<div id="ReplyModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo e(t('Add Notes')); ?> - #<?php echo e($ticket->id); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?php echo e(Form::textarea('note', null, ['class' => 'form-control simpleditor'])); ?>

                            <?php if($errors->has('note')): ?>
                                <div class="error-message"><?php echo e($errors->first('note')); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="display_to_requester" id="display_to_requester"><?php echo e(t('Show this note to Requester')); ?> </label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="email_to_technician" id="email_to_technician">
                                <?php echo e(t('E-mail this note to the technician')); ?></label>
                        </div>
                        
                            
                                
                        

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" class="submitNote"><i class="fa fa-check-circle"></i> Add Note
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(t('Close')); ?></button>
            </div>
        </div>

    </div>
</div>
<?php echo e(Form::close()); ?>