<?php $__env->startSection('header'); ?>
    <h4 class="pull-left">Surveys</h4>
    <a href="<?php echo e(route('admin.survey.create')); ?>" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i></a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('admin.partials._sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <section class="col-sm-9">
    <?php if($surveys->total()): ?>
        <table class="listing-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $surveys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $survey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="col-md-6"><a href="<?php echo e(route('admin.survey.show', $survey)); ?>"><?php echo e($survey->name); ?></a></td>
                    <td class="col-md-3">
                        <a class="btn btn-sm btn-primary" href="<?php echo e(route('admin.survey.edit', $survey)); ?>"><i
                                    class="fa fa-edit"></i> Edit</a>
                        <form action="<?php echo e(route('admin.survey.destroy', $survey)); ?>" method="post" class="inline-block">
                            <?php echo e(csrf_field()); ?> <?php echo e(method_field('delete')); ?>

                            <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <?php echo $__env->make('partials._pagination', ['items' => $surveys], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php else: ?>
        <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>No Surveys found</strong></div>
    <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>