<?php $__env->startSection('header'); ?>
    <h4 class="pull-left"><?php echo e(t('Business Units')); ?></h4>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('stylesheets'); ?>
    <style>
        @keyframes  slideInFromUp {
            0% {
                transform: translateY(-20%);
            }
            100% {
                transform: translateX(0);
            }
        }

        @keyframes  slideInFromRight {
            0% {
                transform: translateX(5%);
            }
            100% {
                transform: translateX(0);
            }
        }

        @keyframes  slideInFromLeft {
            0% {
                transform: translateX(-20%);
            }
            100% {
                transform: translateX(0);
            }
        }

        .card-section {
            animation: .5s ease-out 0s 1 slideInFromUp;
            padding: 30px;
        }
        .logo-animation {
            animation: 1.5s ease-out 0s 1 slideInFromRight;
        }
        .quot-animation{
            animation: 1.5s ease-out 0s 1 slideInFromLeft;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <section class="col-md-12 card-section">
        <div class=form-group></div>


        <div class="tiles-container">
            <?php $__currentLoopData = \App\BusinessUnit::whereHas('categories')->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $business_unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('ticket.create.select_category', $business_unit)); ?>" class="tile" >
                    <div class="tile-container" style="display: flex; flex-direction:column;align-items: center;justify-content: center;">
                        

                        
                        <div class="tile-body" style="width: 100%;height: 100%;display: flex; flex-direction:column;">
                            
                            <p class="text-center logo-animation" style="height: 100px">
                                <img src="<?php echo e($business_unit->url); ?>" alt="<?php echo e($business_unit->url); ?>">
                            </p>
                            
                            <?php if(!$business_unit->logo): ?>
                            <p class="text-center " style="margin-top: 20px;">
                                <?php echo e($business_unit->name); ?>

                            </p>
                            <?php endif; ?>
                            
                            <?php if(str_contains(strtolower($business_unit->name),'quwa')): ?>
                            <p class="text-center quot-animation" style="margin-top: 10px;">
                                نعين ونعاون
                            </p>
                                <?php endif; ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>