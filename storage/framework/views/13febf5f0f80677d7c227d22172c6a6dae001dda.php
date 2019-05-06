<?php if($ticket->resolution): ?>
    <div class="well well-sm well-white resolution-area">
        <div class="row">
            <div class="col-md-4"><p><?php echo e(t('Added by')); ?><?php echo e($ticket->resolution->user->name); ?>

                    at <?php echo e($ticket->resolution->created_at->format('d/m/Y H:i:s')); ?>


                </p></div>
            <div class="col-md-2">
                <?php if(Auth::user()->id == $ticket->resolution->user_id): ?>
                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#editResolution">
                        <?php echo e(t('Edit')); ?>

                    </button>
                <?php endif; ?>
            </div>
        </div>
        <hr style="background-color:black; height: 1px; border: 0; margin:0 0 5px 0px">

        <?php echo tidy_repair_string($ticket->resolution->content,[],'utf8'); ?>

    </div>
<?php elseif(can('resolve', $ticket)): ?>
    <?php echo e(Form::open(['route' => ['ticket.resolution', $ticket],'files'=>'true'])); ?>

    <?php echo e(csrf_field()); ?>


    <div class="form-group">
        <?php echo e(Form::label('content', t('Description'), ['class' => 'control-label'])); ?>

        <?php echo e(Form::textarea('content', null, ['class' => 'form-control richeditor'])); ?>

    </div>

    <div class="row">
        <div class="col-md-4">
            <table class="listing-table table-condensed">
                <thead>
                <tr>
                    <th><?php echo e(t('Attachments')); ?></th>

                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="col-md-10">
                        <input type="file" class="form-control input-xs" name="attachments[]" multiple>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> <?php echo e(t('Add resolution')); ?></button>
    </div>
    <?php echo e(Form::close()); ?>

<?php endif; ?>

<?php if($ticket-> resolution && Auth::user()->id == $ticket->resolution->user_id): ?>
    <div id="editResolution" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo e(t('Edit Resolution')); ?></h4>
                </div>
                <div class="modal-body">
                    <?php echo e(Form::open(['route' => ['ticket.edit-resolution', $ticket]])); ?>

                    <?php echo e(csrf_field()); ?>

                    <div class="form-group">
                        <?php echo e(Form::label('content', 'Description', ['class' => 'control-label'])); ?>

                        <?php echo e(Form::textarea('content',  $ticket->resolution->content ?? '' , ['class' => 'form-control richeditor'])); ?>

                    </div>


                    <div class="form-group">
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> <?php echo e(t('Save')); ?>

                        </button>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(t('Close')); ?></button>
                </div>
            </div>

        </div>
    </div>
<?php endif; ?>

