<div class="col-sm-3">
<section class="panel panel-primary" id="#admin-sidebar">
    <div class="panel-heading">
        <h5 class="panel-title"><?php echo e(t('Navigation')); ?></h5>
    </div>

    <nav class="list-group">

        <p class="list-group-item text-center info"><i class="fa fa-fw fa-group"></i> <strong>Users</strong></p>
        <a href="<?php echo e(route('admin.user.index')); ?>" class="user-link list-group-item"><i class="fa fa-fw fa-user"></i> Users</a>
        <a href="<?php echo e(route('admin.group.index')); ?>" class="user-link list-group-item"><i class="fa fa-fw fa-group"></i> Groups</a>
        <a href="<?php echo e(route('admin.group.user_groups')); ?>" class="user-link list-group-item"><i class="fa fa-fw fa-group"></i> User Groups</a>
        <a href="<?php echo e(route('admin.role.index')); ?>" class="user-link list-group-item"><i class="fa fa-fw fa-group"></i> Roles</a>

        <p class="list-group-item text-center info"><strong><i class="fa fa-fw fa-map"></i> <?php echo e(t('Places')); ?></strong></p>
        <a href="<?php echo e(route('admin.region.index')); ?>" class="list-group-item"><i class="fa fa-fw fa-map"></i> <?php echo e(t('Regions')); ?></a>
        <a href="<?php echo e(route('admin.city.index')); ?>" class="list-group-item"><i class="fa fa-fw fa-map-signs"></i> <?php echo e(t('Cities')); ?></a>
        <a href="<?php echo e(route('admin.location.index')); ?>" class="list-group-item"><i class="fa fa-fw fa-map-marker"></i> <?php echo e(t('Locations')); ?></a>

        <p class="list-group-item text-center info"><strong><i class="fa fa-fw fa-university"></i> <?php echo e(t('Business')); ?></strong></p>
        <a href="<?php echo e(route('admin.business-unit.index')); ?>" class="list-group-item"><i class="fa fa-fw fa-building"></i> <?php echo e(t('Business units')); ?></a>
        <a href="<?php echo e(route('admin.branch.index')); ?>" class="list-group-item"><i class="fa fa-fw fa-sitemap"></i> <?php echo e(t('Branches')); ?></a>
        <a href="<?php echo e(route('admin.department.index')); ?>" class="list-group-item"><i class="fa fa-fw fa-leaf"></i> <?php echo e(t('Departments')); ?></a>

        <p class="list-group-item text-center info"><strong><i class="fa fa-fw fa-cogs"></i> Settings</strong></p>
        <a href="<?php echo e(route('admin.category.index')); ?>" class="list-group-item"><i class="fa fa-fw fa-cubes"></i> Categories</a>
        <a href="<?php echo e(route('admin.status.index')); ?>" class="list-group-item">Status</a>
        <a href="<?php echo e(route('admin.impact.index')); ?>" class="list-group-item"><i class="fa fa-fw fa-bullseye"></i> Impact</a>
        <a href="<?php echo e(route('admin.priority.index')); ?>" class="list-group-item"><i class="fa fa-fw fa-star"></i> Priority</a>
        <a href="<?php echo e(route('admin.urgency.index')); ?>" class="list-group-item"><i class="fa fa-fw fa-hourglass-half"></i> Urgency</a>
        <a href="<?php echo e(route('admin.business-rule.index')); ?>" class="list-group-item"><i class="fa fa-fw fa-magic"></i> Business Rules</a>
        <a href="<?php echo e(route('admin.sla.index')); ?>" class="list-group-item"><i class="fa fa-fw fa-clock-o"></i> Service level agreements</a>
        <a href="<?php echo e(route('admin.survey.index')); ?>" class="list-group-item"><i class="fa fa-fw fa-clock-o"></i> Survey</a>
    </nav>

</section>
</div> 