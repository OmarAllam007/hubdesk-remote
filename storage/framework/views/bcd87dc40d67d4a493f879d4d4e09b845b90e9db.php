<?php $__env->startSection('header'); ?>
    <h4 class="pull-left">Edit Business Unit</h4>

    <form action="<?php echo e(route('admin.business-unit.destroy', $business_unit)); ?>" class="pull-right" method="post">
        <?php echo e(csrf_field()); ?> <?php echo e(method_field('delete')); ?>

        <a href="<?php echo e(route('admin.business-unit.index')); ?>" class="btn btn-sm btn-default"><i class="fa fa-chevron-left"></i></a>
        <button class="btn btn-sm btn-warning" type="submit"><i class="fa fa-trash-o"></i></button>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('admin.partials._sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <?php echo e(Form::model($business_unit, ['route' => ['admin.business-unit.update', $business_unit], 'class' => 'col-sm-9','files'=>true])); ?>


    <?php echo e(method_field('patch')); ?>


    <?php echo $__env->make('admin.business-unit._form', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <?php echo e(Form::close()); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>