<?php $__env->startSection('header'); ?>
    <h4 class="flex" style="margin-bottom: 0">Page not found</h4>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
<div class="col-sm-8 col-sm-offset-2">
    <div class="alert alert-warning text-center">
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>

        <p><i class="fa fa-exclamation-triangle fa-4x"></i></p>

        <p class="lead"><strong>Sorry, the page you are trying to access is not found</strong></p>

        <p>&nbsp;</p>

        <p><strong><small>URL: <a href="<?php echo e(request()->getUri()); ?>"><?php echo e(request()->getUri()); ?></a></small></strong></p>

        <p>&nbsp;</p>

        <p><a href="<?php echo e(URL::previous('/')); ?>" class="btn btn-default">
                <span class="text-warning"><i class="fa fa-chevron-left"></i> Go Back</span>
            </a></p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>


    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>