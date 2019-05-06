<?php if($ticket->approvals->count()): ?>
    <table class="listing-table">
        <thead class="table-design">
        <tr>
            <th><?php echo e(t('Sent to')); ?></th>
            <th><?php echo e(t('By')); ?></th>
            <th><?php echo e(t('Sent at')); ?></th>
            <th><?php echo e(t('By')); ?></th>
            <th><?php echo e('Sent at'); ?></th>
            <th><?php echo e(t('Stage')); ?></th>
            <th><?php echo e(t('Status')); ?></th>
            <th><?php echo e(t('Comment')); ?></th>
            <th><?php echo e(t('Action Date')); ?></th>
            <th><?php echo e(t('Resend')); ?></th>
            <th colspan="3" class="text-center"><?php echo e(t('Actions')); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php $__currentLoopData = $ticket->approvals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $approval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="<?php echo e($approval->approval_color); ?>">

                <td><?php echo e($approval->approver->name); ?></td>
                <td><?php echo e($approval->created_by->name); ?></td>
                <td><?php echo e($approval->created_at->format('d/m/Y H:i')); ?></td>
                <td><?php echo e($approval->stage); ?></td>
                <td>
                    <i class="fa fa-lg fa-<?php echo e($approval->approval_icon); ?> text-<?php echo e($approval->approval_color); ?>"
                       aria-hidden="true"></i>
                    <?php echo e($approval->approval_status); ?>

                </td>
                <td><strong><?php echo e($approval->comment); ?></strong></td>
                <td><?php echo e($approval->action_date); ?></td>
                <td><?php echo e($approval->resend); ?></td>
                <td>
                    <?php if($approval->pending && $approval->approver_id == \Auth::user()->id &&  !$ticket->isClosed()): ?>
                        <a title="Take Action" href="<?php echo e(route('approval.show', $approval)); ?>" class="btn btn-xs btn-info"><i
                                    class="fa fa-gavel"></i></a>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($approval->shouldSend()): ?>
                        <?php if($approval->pending && Auth::user()->id == $approval->creator_id): ?>
                            <a title="Resend approval" href="<?php echo e(route('approval.resend', $approval)); ?>"
                               class="btn btn-xs btn-primary"><i class="fa fa-refresh"></i></a>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($approval->pending): ?>
                        <?php if(can('delete',$approval)): ?>
                            <?php echo e(Form::open(['route' => ['approval.destroy', $approval], 'method' => 'delete'])); ?>

                            <button type="submit" title="Remove approval" class="btn btn-xs btn-warning">
                                <i class="fa fa-remove"></i>
                            </button>
                            <?php echo e(Form::close()); ?>

                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> <?php echo e(t('No approvals yet')); ?></div>
<?php endif; ?>

<?php if(Auth::user()->isSupport() && !$ticket->isClosed()): ?>
    <section id="approvalForm">
        <?php echo e(Form::open(['route' => ['approval.send', $ticket],'files' => true])); ?>


        <?php if($ticket->hasApprovalStages()): ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group form-group-sm <?php echo e($errors->has('approver_id')? 'has-error' : ''); ?>">
                        <?php echo e(Form::label('stage', 'Approval Stage', ['class' => 'control-label'])); ?>

                        <?php echo e(Form::select('stage', $ticket->approvalStages(), old('stage', $ticket->approvals->max('stage')), ['class' => 'form-control'])); ?>

                        <?php if($errors->has('stage')): ?>
                            <div class="error-message"><?php echo e($errors->first('stage')); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group form-group-sm <?php echo e($errors->has('approver_id')? 'has-error' : ''); ?>">
                    <?php echo e(Form::label('approver_id', t('Send approval to'), ['class' => 'control-label'])); ?>

                    <select class="form-control select2" name="approver_id" id="approver_id">
                        <option value=""><?php echo e(t('Select Approver')); ?></option>
                        <?php $__currentLoopData = App\User::where('email','<>',null)->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>">
                                <?php echo e($user->name); ?> ( <?php echo e($user->email); ?> )
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    
                    <?php if($errors->has('approver_id')): ?>
                        <div class="error-message"><?php echo e($errors->first('approver_id')); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="form-group <?php echo e($errors->has('content')? 'has-error' : ''); ?>">
            <?php echo e(Form::label('content', t('Description'), ['class' => 'control-label'])); ?>

            <?php echo e(Form::textarea('content', null, ['class' => 'form-control richeditor'])); ?>


            <?php if($errors->has('content')): ?>
                <div class="error-message"><?php echo e($errors->first('content')); ?></div>
            <?php endif; ?>
        </div>

        <?php if($ticket->approvals->count()): ?>
            <div class="checkbox">
                <label>
                    <?php echo e(Form::checkbox('add_stage')); ?> <?php echo e(t('Add approval in a new stage')); ?>

                </label>
            </div>
        <?php endif; ?>

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
            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> <?php echo e(t('Send')); ?></button>
        </div>
        <?php echo e(Form::close()); ?>

    </section>
<?php endif; ?>