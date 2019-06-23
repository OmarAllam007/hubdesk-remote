<?php $__env->startSection('header'); ?>
    <h4><?php echo e(t('Admin')); ?></h4>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-user"></i> <?php echo e(t('Users')); ?></h3>
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-pills">
                            <li><a href="<?php echo e(route('admin.user.index')); ?>"><?php echo e(t('Users')); ?></a></li>
                            <li><a href="<?php echo e(route('admin.group.index')); ?>"><?php echo e(t('Groups')); ?></a></li>
                            <li><a href="<?php echo e(route('admin.group.user_groups')); ?>"><?php echo e(t('User Groups')); ?></a></li>
                            <li><a href="<?php echo e(route('admin.role.index')); ?>"><?php echo e(t('Roles')); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-cubes"></i> <?php echo e(t('Categories')); ?></h3>
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-pills">
                            <li><a href="<?php echo e(route('admin.category.index')); ?>"><?php echo e(t('Categories')); ?></a></li>
                            <li><a href="<?php echo e(route('admin.subcategory.index')); ?>"><?php echo e(t('Subcategories')); ?></a></li>
                            <li><a href="<?php echo e(route('admin.item.index')); ?>"><?php echo e(t('Items')); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-map-marker"></i> <?php echo e(t('Locations')); ?></h3>
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-pills">
                            <li><a href="<?php echo e(route('admin.region.index')); ?>"><?php echo e(t('Regions')); ?></a></li>
                            <li><a href="<?php echo e(route('admin.city.index')); ?>"><?php echo e(t('Cities')); ?></a></li>
                            <li><a href="<?php echo e(route('admin.location.index')); ?>"><?php echo e(t('Location')); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <hr/>
            </div>

            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-building"></i>  <?php echo e(t('Business Units')); ?></h3>
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-pills">
                            <li><a href="<?php echo e(route('admin.business-unit.index')); ?>"><?php echo e(t('Business Units')); ?></a></li>
                            <li><a href="<?php echo e(route('admin.branch.index')); ?>"><?php echo e(t('Branches')); ?></a></li>
                            <li><a href="<?php echo e('admin.department.index'); ?>"><?php echo e(t('Departments')); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-cogs"></i> <?php echo e(t('Configuration')); ?></h3>
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-pills">
                            <li><a href="<?php echo e(route('admin.sla.index')); ?>"><?php echo e(t('Service Level Agreement')); ?></a></li>
                            <li><a href="<?php echo e(route('admin.business-rule.index')); ?>"><?php echo e(t('Business Rules')); ?></a></li>
                            <li><a href="<?php echo e(route('admin.priority.index')); ?>"><?php echo e(t('Priority')); ?></a></li>
                            <li><a href="<?php echo e(route('admin.urgency.index')); ?>"><?php echo e(t('Urgency')); ?></a></li>
                            <li><a href="<?php echo e(route('admin.impact.index')); ?>"><?php echo e(t('Impact')); ?></a></li>
                            <li><a href="<?php echo e(route('admin.status.index')); ?>"><?php echo e(t('Status')); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo e(t('Survey')); ?></h3>
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-pills">
                            <li><a href="<?php echo e(route('admin.survey.index')); ?>"><?php echo e(t('Surveys')); ?></a></li>
                            <li><a href="<?php echo e(route('admin.survey.create')); ?>"><?php echo e(t('Define Survey')); ?></a></li>
                            <li><a href="<?php echo e(route('admin.sla.index')); ?>"><?php echo e(t('Survey Log')); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>