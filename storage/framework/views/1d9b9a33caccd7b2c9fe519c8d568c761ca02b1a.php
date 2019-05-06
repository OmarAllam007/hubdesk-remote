<tasks :ticket_id="<?php echo e($ticket->id); ?>" inline-template>
    <div>
        <div>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('task_create',$ticket)): ?>
                <button data-toggle="modal" data-target="#TaskForm" type="button" @click="resetTask"
                        class="btn btn-sm btn-success pull-right" title="<?php echo e(t('Create Task')); ?>">
                    <i class="fa fa-plus"></i> <?php echo e(t('Create Task')); ?>

                </button>
            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
        <br>
        <table class="listing-table" v-if="tasks[0]" >
            <thead class="table-design">
            <tr>
                <th><?php echo e(t('ID')); ?></th>
                <th><?php echo e(t('Subject')); ?></th>
                <th><?php echo e(t('Status')); ?></th>
                <th><?php echo e(t('Created At')); ?></th>
                <th><?php echo e(t('Created By')); ?></th>
                <th><?php echo e(t('Assigned To')); ?></th>
                <th><?php echo e(t('Actions')); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="task in tasks">
                <td class="col-md-1"><a v-bind:href="'/ticket/'+ task.id">{{ task.id }}</a></td>
                <td class="col-md-2"> {{ task.subject }}</td>
                <td class="col-md-1"> {{ task.status }}</td>
                <td class="col-md-2"> {{ task.created_at }}</td>
                <td class="col-md-2"> {{ task.requester }}</td>
                <td class="col-md-2"> {{ task.technician }}</td>
                <td class="col-md-3">
                    <a class="btn btn-rounded  btn-info" v-bind:href="'/ticket/'+ task.id">
                        <i class="fa fa-eye"></i>
                        <?php echo e(t('Show')); ?>

                    </a>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('modify',$ticket)): ?>
                    <a  class="btn btn-rounded btn-warning"
                       :href="'/ticket/tasks/edit/'+ task.id">
                        <i class="fa fa-edit"></i>
                        <?php echo e(t('Edit')); ?>

                    </a>
                    <?php endif; ?>
                    
                </td>
            </tr>

            </tbody>
        </table>
        <div class="alert alert-info" v-else="tasks[0]"><i class="fa fa-exclamation-circle"></i>
            <strong><?php echo e(t('No Tasks found')); ?></strong>
        </div>
        <?php echo $__env->make('ticket._create_task', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>
</tasks>
