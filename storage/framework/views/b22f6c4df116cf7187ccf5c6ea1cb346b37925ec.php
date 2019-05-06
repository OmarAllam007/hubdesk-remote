<?php $__env->startSection('header'); ?>
    <h4 class="pull-left"><?php echo e(t('Business Units')); ?></h4>
    <form action="" class="form-inline" method="get">
        <div class="input-group">
            <input class="form-control input-sm" type="search" name="search" id="searchTerm" placeholder="Search"
                   value="<?php echo e(Request::query('search', '')); ?>">
            <span class="input-group-btn">
                    <button class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
                </span>
        </div>
        <a href="<?php echo e(route('admin.business-unit.create')); ?>" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></a>
        
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('admin.partials._sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <div class="col-sm-9">
        <?php if($businessUnits->total()): ?>
            <table class="listing-table">
                <thead>
                <tr>
                    <th><?php echo e(t('Name')); ?></th>
                    <th><?php echo e(t('Location')); ?></th>
                    <th><?php echo e(t('Actions')); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $businessUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $businessUnit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="col-md-5"><a href="<?php echo e(route('admin.business-unit.edit', $businessUnit)); ?>"><?php echo e($businessUnit->name); ?></a></td>
                        <td class="col-md-4"><?php echo e(isset($businessUnit->location->name) ? $businessUnit->location->name : ''); ?></td>
                        <td class="col-md-3">
                            <a class="btn btn-sm btn-primary" href="<?php echo e(route('admin.business-unit.edit', $businessUnit)); ?>"><i class="fa fa-edit"></i> Edit</a>
                            <form action="<?php echo e(route('admin.business-unit.destroy', $businessUnit)); ?>" method="post" class="inline-block">
                                <?php echo e(csrf_field()); ?> <?php echo e(method_field('delete')); ?>

                                <button class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <?php echo $__env->make('partials._pagination', ['items' => $businessUnits], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php else: ?>
            <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>No branches found</strong></div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>