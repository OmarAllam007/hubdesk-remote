
<?php if($ticket->replies->count() || $ticket->approvals->count()): ?>
    <section class="replies">

        <div class="form-group clearfix">
            <a href="#ReplyForm" class="btn btn-primary pull-right"><i class="fa fa-commenting"></i> <?php echo e(t('Add reply')); ?>

            </a>
        </div>

        <div class="conversation-replies">
            <?php $__currentLoopData = $ticket->replies()->latest()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="panel panel-sm panel-<?php echo e($reply->class); ?>">
                    <div class="panel-heading">
                        <h5 class="panel-title"><a href="#reply<?php echo e($reply->id); ?>" data-toggle="collapse"><?php echo e(t('By')); ?>

                                : <?php echo e($reply->user->name); ?> On <?php echo e($reply->created_at->format('d/m/Y H:i A')); ?></a></h5>
                    </div>
                    <div class="panel-body collapse" id="reply<?php echo e($reply->id); ?>">
                        <div class="reply">
                            <?php echo tidy_repair_string($reply->content, [], 'utf8'); ?>

                        </div>
                        <br>
                        <span class="label label-default">Status: <?php echo e(t($reply->status->name)); ?></span>
                        <?php if($reply->to): ?><span class="label label-primary">To: <?php echo e(implode(", ",$reply->to)); ?></span><?php endif; ?>
                        <?php if($reply->cc): ?><span class="label label-primary">Cc: <?php echo e(implode(", ",$reply->cc)); ?></span><?php endif; ?>


                        <?php if($reply->attachments->count()): ?>
                            <br><br>
                            <p><strong>Attachments</strong></p>
                            <?php $__currentLoopData = $reply->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show_approvals',$ticket)): ?>
            <?php echo $__env->make('ticket.partials._ticket_approvals', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php endif; ?>

    </section>
<?php endif; ?>

<div id="ReplyForm">
    <?php echo e(Form::open(['route' => ['ticket.reply', $ticket], 'files' => true])); ?>

    <?php echo e(csrf_field()); ?>

 
    <div class="row">
        <div class="col-md-6">
            <div class="form-group form-group-sm <?php echo e($errors->has('cc')? 'has-error' : ''); ?>">
                <?php echo e(Form::label('reply[cc]', t('Cc'), ['class' => 'control-label'])); ?>

                <select class="form-control select2" name="reply[cc][]" multiple>
                    <option value="" disabled="disabled"><?php echo e(t('Select')); ?></option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->email); ?>"><?php echo e($user->name); ?> - <?php echo e($user->email); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <?php if(count($errors) && $errors->has('cc.*')): ?>
                    <div class="error-message">
                        <?php echo e($errors->first()); ?>


                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="form-group <?php echo e($errors->has('reply.content')? 'has-errors' : ''); ?>">
        <?php echo e(Form::label('reply[content]', t('Description'), ['class' => 'control-label'])); ?>

        <?php echo e(Form::textarea('reply[content]', null, ['class' => 'form-control richeditor', 'rows' => 5])); ?>

        <?php if($errors->has('reply.content')): ?>
            <div class="error-message"><?php echo e($errors->first('reply.content')); ?></div>
        <?php endif; ?>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group <?php echo e($errors->has('reply.status_id')? 'has-error' : ''); ?>">
                <?php echo e(Form::label('reply[status_id]', t('Change status from') .' ( '. t($ticket->status->name) . ' ) '.t('to'), ['class' => 'control-label'])); ?>

                <?php echo e(Form::select('reply[status_id]', t(App\Status::reply($ticket)->selection('Select Status')), 5, ['class' => 'form-control'])); ?>

                <?php if($errors->has('reply.status_id')): ?>
                    <div class="error-message"><?php echo e($errors->first('status_id')); ?></div>
                <?php endif; ?>
            </div>
        </div>
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
        <button type="submit" class="btn btn-primary"><i class="fa fa-send"></i> <?php echo e(t('Send')); ?></button>
    </div>
    <?php echo e(Form::close()); ?>

</div>
