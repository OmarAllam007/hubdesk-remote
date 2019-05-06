<?php $__env->startSection('header'); ?>
    <h4 class="panel-title">Login</h4>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
    <div style="width: 100%;display: flex;justify-content: center;align-items: center;padding: 1px">
        <div class="auth-form">
            <form style="width: 100%;align-content: center" class="form-horizontal" role="form" method="POST" action="<?php echo e(url('/login')); ?>">
                <?php echo csrf_field(); ?>

                
                <div class="form-group">
                    <div class="col-sm-12 text-center">
                        <img src="<?php echo e(asset('images/hubdesk.png')); ?>" style="width:66.66666667%" alt="">
                    </div>
                </div>
                <br>
                
                <div class="form-group<?php echo e($errors->has('login') ? ' has-error' : ''); ?>">
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="login" id="login" value="<?php echo e(old('login')); ?>" placeholder="<?php echo e(t('SAP ID')); ?>"  style="width: 100%">
                        <?php if($errors->has('login')): ?>
                            <span class="error-message"><?php echo e($errors->first('login')); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                    <div class="col-sm-12">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                        <?php if($errors->has('password')): ?>
                            <span class="error-message"><?php echo e($errors->first('password')); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-8">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember"> Remember Me
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12 text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-btn fa-sign-in"></i> Login
                        </button>

                        <a href="/auth/google" class="btn btn-danger">
                            <i class="fa fa-btn fa-google-plus"></i> Login using Google
                        </a>

                        <a href="<?php echo e(route('password.request')); ?>" class="btn btn-success">
                            <i class="fa fa-btn fa-unlock"></i> Reset Password
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>