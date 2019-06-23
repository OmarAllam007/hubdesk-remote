<?php $__env->startSection('header'); ?>

    <div class="display-flex">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('ticket.create-wizard')); ?>">
                        <?php echo e(t('Select Company')); ?>

                    </a>
                </li>
                
                <li class="breadcrumb-item">
                        <a href="<?php echo e(route('ticket.create.select_category',compact('business_unit','category'))); ?>">
                        <?php echo e(t($business_unit->name)); ?>

                    </a>
                </li>
                <li class="breadcrumb-item"> <?php echo e(t($category->name)); ?>

                   </li>
                

                
            </ol>
        </nav>
    </div>

    <style>
        @keyframes  slideInFromLeft {
            0% {
                transform: translateY(-10%);
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
    <section class="col-sm-12 card-section">
        <?php if($business_unit->name): ?>
        <?php endif; ?>

            <div class=form-group></div>

            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="tiles-container">
                        <?php $__currentLoopData = $category->subcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($subcategory->canDisplay(\App\ServiceUserGroup::$SUBCATEGORY)): ?>

                            <a href="<?php echo e(route('ticket.create.select_item', compact('business_unit','category','subcategory'))); ?>" class="tile">
                                <div class="tile-container"
                                     style="display: flex;align-items: center;justify-content: center;">
                                    
                                    
                                    
                                    <div class="tile-body" style="display: flex;">
                                        <p class="text-center">
                                            <?php echo e(t($subcategory->name)); ?>

                                        </p>
                                    </div>
                                </div>
                            </a>
                            <?php endif; ?>
                            

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>