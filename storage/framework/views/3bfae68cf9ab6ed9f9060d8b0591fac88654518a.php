<?php $__env->startSection('header'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show',$ticket)): ?>
        <div class="display-flex ticket-meta">
            <div class="flex">
                <h4>#<?php echo e($ticket->id); ?> - <?php echo e($ticket->subject); ?>

                </h4>
                <h4>
                    <strong><?php echo e(t('By')); ?> :<?php echo e($ticket->requester->name); ?></strong>
                </h4>
                <?php if($ticket->isDuplicated()): ?>
                    <h4><?php echo e('Duplicated Request from'); ?>:
                        <a title="Show Original Request" href="<?php echo e(route('ticket.show',$ticket->request_id)); ?>"
                           target="_blank">
                            #<?php echo e($ticket->request_id); ?>

                        </a>
                    </h4>
                <?php endif; ?>

                <?php if(Auth::user()->isSupport()): ?>
                    <?php if($ticket->isTask()): ?>
                        <h4><?php echo e(t('Request')); ?>:
                            <a title="<?php echo e(t('Show Original Request')); ?>"
                               href="<?php echo e(route('ticket.show',$ticket->request_id)); ?>"
                               target="_blank">
                                #<?php echo e($ticket->request_id); ?>

                            </a>
                        </h4>
                    <?php endif; ?>
                    <div class="btn-toolbar">

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reassign',$ticket)): ?>
                            <button data-toggle="modal" data-target="#AssignForm" type="button"
                                    class="btn btn-sm btn-info btn-rounded btn-outlined" title="<?php echo e(t('Re-assign')); ?>">
                                <i class="fa fa-mail-forward"></i> <?php echo e(t('Re-assign')); ?>

                            </button>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('forward',$ticket)): ?>
                            <button data-toggle="modal" data-target="#ForwardForm" type="button"
                                    class="btn btn-sm btn-primary btn-rounded btn-outlined" title="<?php echo e(t('Forward')); ?>">
                                <i class="fa fa-arrow-circle-right"></i> <?php echo e(t('Forward')); ?>

                            </button>
                        <?php endif; ?>
                        <?php if(!$ticket->isTask()): ?>
                            <button data-toggle="modal" data-target="#DuplicateForm" type="button"
                                    class="btn btn-sm btn-primary btn-rounded btn-outlined" title="Duplicate">
                                <i class="fa fa-copy"></i> <?php echo e(t('Duplicate')); ?>

                            </button>

                            <button type="button" class="btn btn-primary btn-sm btn-rounded btn-outlined addNote"
                                    data-toggle="modal" data-target="#ReplyModal" title="<?php echo e(t('Add Note')); ?>">
                                <i class="fa fa-sticky-note"></i> <?php echo e(t('Add Note')); ?>

                            </button>

                            
                            
                            
                            
                            

                            <a href="<?php echo e(route('ticket.print',$ticket)); ?>" target="_blank"
                               class="btn btn-sm btn-primary btn-rounded btn-outlined" title="Print">
                                <i class="fa fa-print"></i> <?php echo e(t('Print')); ?>

                            </a>
                        <?php endif; ?>

                        <?php if($ticket->isTask()): ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('modify',$ticket)): ?>
                                <a href="<?php echo e(route('tasks.edit',$ticket)); ?>"
                                   class="btn btn-sm btn-primary btn-rounded btn-outlined" title="Edit">
                                    <i class="fa fa-edit"></i> <?php echo e(t('Edit')); ?>

                                </a>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('send_to_finance',$ticket)): ?>
                            <button type="button" class="btn btn-primary btn-sm btn-rounded btn-outlined"
                                    data-toggle="modal" data-target="#FinanceForm" title="<?php echo e(t('Send to Finance')); ?>">
                                <i class="fa fa-money"></i> <?php echo e(t('Send to Finance')); ?>

                            </button>
                        <?php endif; ?>

                    </div>
                <?php endif; ?>

            </div>


            <div class="card">
                <ul class="list-unstyled">
                    <li>
                        <?php if($ticket->overdue): ?>
                            <i class="fa fa-flag text-danger" aria-hidden="true"
                               title="<?php echo e(t('SLA violated')); ?>"></i>
                        <?php endif; ?>
                        <small><strong><?php echo e(t('Status')); ?>:</strong> <?php echo e(t($ticket->status->name)); ?></small>
                    </li>
                    <li>
                        <small><strong><?php echo e(t('Created at')); ?>:</strong> <?php echo e($ticket->created_at->format('d/m/Y H:i')); ?>

                        </small>
                    </li>
                    <?php if($ticket->due_date): ?>
                        <li>
                            <small><strong><?php echo e(t('Due Date')); ?> :</strong> <?php echo e($ticket->due_date->format('d/m/Y H:i')); ?>

                            </small>
                        </li>
                    <?php endif; ?>

                    <?php if($ticket->resolve_date): ?>
                        <li>

                            <small><strong><?php echo e(t('Resolve Date')); ?>

                                    :</strong> <?php echo e($ticket->resolve_date->format('d/m/Y H:i')); ?>

                            </small>
                        </li>
                    <?php endif; ?>
                    <?php if($ticket->last_updated_approval): ?>
                        <li>
                            <small>
                                <strong><?php echo e(t('Approval Status')); ?>:</strong>
                                <i class="fa fa-lg fa-<?php echo e($ticket->last_updated_approval->approval_icon); ?> text-<?php echo e($ticket->last_updated_approval->approval_color); ?>"
                                   aria-hidden="true"></i> <?php echo e(\App\TicketApproval::$statuses[$ticket->last_updated_approval->status]); ?>

                            </small>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <?php 
    $users = \App\User::whereNotNull('email')->orderBy('name')->get();
     ?>

    <?php if(can('show',$ticket)): ?>
        <section class="col-sm-12" id="ticketArea">
            <ul class="nav nav-tabs" role="tablist">
                <li class="active">
                    <a href="#main" role="tab" data-toggle="tab"><i
                                class="fa fa-ticket"></i>
                        <?php if(!$ticket->isTask()): ?>
                            <?php echo e(t('Request')); ?>

                        <?php else: ?>
                            <?php echo e(t('Task')); ?>

                        <?php endif; ?>

                    </a>
                </li>
                <li><a href="#conversation" role="tab" data-toggle="tab"><i
                                class="fa fa-comments-o"></i> <?php echo e(t('Conversation')); ?></a></li>
                
                <?php if($ticket->resolution || can('resolve', $ticket)): ?>
                    <li><a href="#resolution" role="tab" data-toggle="tab"><i
                                    class="fa fa-support"></i> <?php echo e(t('Resolution')); ?></a></li>
                <?php endif; ?>
                <?php if(($ticket->approvals->count() || Auth::user()->isSupport()) && !$ticket->isTask()): ?>
                    <li><a href="#approvals" role="tab" data-toggle="tab"><i
                                    class="fa fa-check"></i> <?php echo e(t('Approvals')); ?></a></li>
                <?php endif; ?>

                <?php if(Auth::user()->isSupport() && !$ticket->isTask()): ?>
                    <li><a href="#tasks" role="tab" data-toggle="tab"><i
                                    class="fa fa-tasks"></i> <?php echo e(t('Tasks')); ?></a></li>
                <?php endif; ?>

                <li><a href="#history" role="tab" data-toggle="tab"><i
                                class="fa fa-history"></i>

                        <?php if(!$ticket->isTask()): ?>
                            <?php echo e(t('Ticket Log')); ?>

                        <?php else: ?>
                            <?php echo e(t('Task Log')); ?>

                        <?php endif; ?>
                    </a></li>

                <?php if($ticket->files->count()): ?>
                    <li><a href="#attachments" role="tab" data-toggle="tab"><i
                                    class="fa fa-file-o"></i> <?php echo e(t('Attachments')); ?></a></li>
                <?php endif; ?>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="main">
                    <?php echo $__env->make('ticket.tabs._main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div>

                <div role="tabpanel" class="tab-pane" id="resolution">
                    <?php echo $__env->make('ticket.tabs._resolution', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div>

                <div role="tabpanel" class="tab-pane" id="conversation">
                    <?php echo $__env->make('ticket.tabs._conversation', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div>


                <div role="tabpanel" class="tab-pane" id="resolution">
                    <?php echo $__env->make('ticket.tabs._resolution', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div>

                <div role="tabpanel" class="tab-pane" id="history">
                    <?php echo $__env->make('ticket.tabs._history', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div>

                <div role="tabpanel" class="tab-pane" id="approvals">
                    <?php echo $__env->make('ticket.tabs._approvals', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div>


                <?php if($ticket->files->count()): ?>
                    <div role="tabpanel" class="tab-pane" id="attachments">
                        <?php echo $__env->make('ticket.tabs._attachment', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </div>
                <?php endif; ?>


                <div role="tabpanel" class="tab-pane" id="tasks">
                    <?php echo $__env->make('ticket.tabs.tasks', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div>

                <?php echo $__env->make('ticket._assign_modal', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php echo $__env->make('ticket.partials._send_to_finance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php echo $__env->make('ticket._forward_modal', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php echo $__env->make('ticket._notes_modal', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php echo $__env->make('ticket._remove_note_modal', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php echo $__env->make('ticket._duplicate_modal', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                <script>
                    var category = '<?php echo e(Form::getValueAttribute('category_id') ?? $ticket->category_id); ?>';
                    var subcategory = '<?php echo e(Form::getValueAttribute('subcategory_id') ?? $ticket->subcategory_id); ?>';
                    var group = '<?php echo e(Form::getValueAttribute('group_id') ?? $ticket->group_id); ?>';
                </script>
                <script src="<?php echo e(asset('/js/tasks.js')); ?>"></script>
            </div>
        </section>
    <?php else: ?>

        <div class="container-fluid">
            <div class="alert alert-warning text-center"><i class="fa fa-exclamation-circle"></i>
                <strong>
                    <?php echo e(t('You are not authorized to display this request')); ?>

                </strong>
            </div>
        </div>

    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
    <script src="<?php echo e(asset('/js/ticket.js')); ?>"></script>
    <script src="<?php echo e(asset('/js/tinymce/tinymcebasic.min.js')); ?>"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>