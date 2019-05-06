<?php if($category ): ?>
    <?php $__currentLoopData = $category->custom_fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-sm-6">
        <?php echo $__env->make('custom-fields.' . $field['type'], compact('field'), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<?php if($subcategory ): ?>
    <?php $__currentLoopData = $subcategory->custom_fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-sm-6">
        <?php echo $__env->make('custom-fields.' . $field['type'], compact('field'), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<?php if($item && count($item->custom_fields)): ?>
    <?php $__currentLoopData = $item->custom_fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('custom-fields.' . $field['type'], compact('field'), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>