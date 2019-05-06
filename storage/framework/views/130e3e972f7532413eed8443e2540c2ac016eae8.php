<?php $__env->startSection('header'); ?>

    <div class="display-flex">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo e(t('Home')); ?></li>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('ticket.create-wizard')); ?>"><?php echo e(t($business_unit->name)); ?>

                    </a>
                </li>
                
                    
                

            </ol>
        </nav>
    </div>

    <style>
        @keyframes  slideInFromLeft {
            0% {
                transform: translateX(-20%);
            }
            100% {
                transform: translateX(0);
            }
        }

        .card-section {
            animation: .5s ease-out 0s 1 slideInFromLeft;
            padding: 30px;
        }
    </style>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('body'); ?>
    
    <section class="col-sm-12 card-section" >

        <div class=form-group></div>

        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="tiles-container">
                    <?php if(str_contains(strtolower($business_unit->name),'quwa')): ?>
                        <a target="_blank"
                           href="https://fiori.alkifah.com:5447/sap/bc/ui5_ui5/ui2/ushell/shells/abap/FioriLaunchpad.html?sap-sec_session_created=X&sap-sec_session_created=X"
                           class="tile">
                            <div class="tile-container"
                                 style="display: flex;align-items: center;justify-content: center;">
                                <div class="tile-body"
                                     style="display: flex;flex-direction: column;justify-content: center">
                                    <img src="<?php echo e(asset('images/fiori-logo.jpeg')); ?>">
                                    
                                    
                                    
                                    
                                    
                                    
                                </div>
                            </div>
                        </a>
                    <?php endif; ?>

                    <?php $__currentLoopData = $business_unit->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('ticket.create.select_subcategory', compact('business_unit','category'))); ?>"
                           class="tile">
                            <div class="tile-container"
                                 style="display: flex;align-items: center;justify-content: center;">
                                <div class="tile-body"
                                     style="display: flex;flex-direction: column;justify-content: center">
                                    <p class="text-center">
                                        <?php echo e(t($category->name)); ?>

                                    </p>
                                    <p>
                                        <?php echo e($category->service_cost ? $category->service_cost : ''); ?>

                                    </p>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>

    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>