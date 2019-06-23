<?php $__env->startSection('header'); ?>
    <h4 class="flex"><?php echo e(t('Tickets')); ?></h4>

    <?php echo e(Form::open(['route' => 'ticket.scope', 'class' => 'form-inline ticket-scope heading-actions flex'])); ?>

    <div class="btn-group">
        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
            <?php echo e(t($scopes[$scope])); ?> &nbsp; <span class="count"><?php echo e(\App\Ticket::scopedView($scope)->count()); ?></span>
            &nbsp; <span class="caret"></span>
        </button>

        <ul class="dropdown-menu">
            <?php $__currentLoopData = $scopes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <button class="btn btn-link btn-sm" type="submit" name="scope"
                            value="<?php echo e($key); ?>"><?php echo e(t($value)); ?></button>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php echo e(Form::close()); ?>


    <?php echo e(Form::open(['route' => 'ticket.jump', 'class' => 'form-inline heading-actions'])); ?>

    <div class="input-group input-group-sm">
        <input class="form-control" type="text" name="id" id="ticketID" placeholder="<?php echo e(t('Ticket ID')); ?>"/>
        <span class="input-group-btn">
            <button class="btn btn-default"><i class="fa
                         <?php if(\Session::get('personlized-language-ar' . \Auth::user()->id, \Config::get('app.locale'))=="ar"): ?>
                        fa-chevron-left
                        <?php else: ?>
                        fa-chevron-right
                        <?php endif; ?>
                        "></i></button>
        </span>
    </div>
    
    <a href="#SearchForm" data-toggle="collapse" class="btn btn-info btn-sm searchbtn"><i class="fa fa-search"></i></a>
    <?php echo e(Form::close()); ?>

    <style>
        .ticket-card {
            display: flex;
            margin: 5px;
            background-color: white;
            padding: 20px;
            padding-right: 100px;
            width: 80%;
            justify-content: space-between;
            box-shadow: 2px 2px 2px darkgrey;
            border-left: 2px solid #636b6f;
            /*border-radius: 5px;*/
        }

        .ticket-container {
            display: flex;
            flex-direction: column;
            /*justify-content: center;*/
            /*align-content: center;*/
            align-items: center;
        }

        .subject {
            font-size: 12pt;
            font-weight: bold;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    
    

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    


    <section class="col-sm-12" id="TicketList">
        <?php echo $__env->make('ticket._search_form', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php if($tickets->total()): ?>
            <table class="table table-striped index-table" id="index-table" style="  border-collapse: collapse;
  border-radius: 1em;
  overflow: hidden;box-shadow: 0 0 3px">
                <thead style="  padding: 1em;">
                <tr>
                    <th><?php echo e(t('ID')); ?></th>
                    
                    <th><?php echo e(t('Subject')); ?></th>
                    <th><?php echo e(t('Requester')); ?></th>
                    <th><?php echo e(t('SAP ID')); ?></th>
                    <th><?php echo e(t('Technician')); ?></th>
                    <th><?php echo e(t('Created At')); ?></th>
                    <th><?php echo e(t('Due Date')); ?></th>
                    <th><?php echo e(t('Status')); ?></th>
                    <th><?php echo e(t('Category')); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><i class="fa fa-<?php echo e(t($ticket->type_icon)); ?>" title="<?php echo e(t($ticket->type_name)); ?>"
                               aria-hidden="true"></i> <a
                                    href="<?php echo e(route('ticket.show', $ticket)); ?>"><?php echo e($ticket->id); ?></a></td>
                        
                        <td>
                            <?php if($ticket->overdue): ?>
                                <i class="fa fa-flag text-danger" aria-hidden="true" title="SLA violated"></i>
                            <?php endif; ?>
                            <a href="<?php echo e(route('ticket.show', $ticket)); ?>"><?php echo e($ticket->subject); ?></a>
                        </td>
                        <td><?php echo e($ticket->requester->name); ?></td>
                        <td><?php echo e($ticket->requester->employee_id ?? t('Not Assigned')); ?></td>
                        <td><?php echo e($ticket->technician? $ticket->technician->name : 'Not Assigned'); ?></td>
                        <td><?php echo e($ticket->created_at->format('d/m/Y h:i a')); ?></td>
                        <td><?php echo e($ticket->due_date? $ticket->due_date->format('d/m/Y h:i a') : t('Not Assigned')); ?></td>
                        <td><?php echo e(t($ticket->status->name)); ?></td>
                        <td><?php echo e(t($ticket->category->name)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <?php echo $__env->make('partials._pagination', ['items' => $tickets], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php else: ?>
            <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>No tickets found</strong>
            </div>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
    <script src="<?php echo e(asset('/js/ticket-index.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>