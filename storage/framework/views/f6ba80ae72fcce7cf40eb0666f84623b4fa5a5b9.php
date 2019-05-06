<div class="row">
    <div class="col-md-6">
        <?php echo e(csrf_field()); ?>


        <div class="form-group <?php echo e($errors->has('name')? 'has-errors' : ''); ?>">
            <?php echo e(Form::label('name', 'Name', ['class' => 'control-label'])); ?>

            <?php echo e(Form::text('name', null, ['class' => 'form-control'])); ?>

            <?php if($errors->has('name')): ?>
                <div class="error-message"><?php echo e($errors->first('name')); ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group <?php echo e($errors->has('category_id')? 'has-errors' : ''); ?>">
            <?php echo e(Form::label('category_id', 'Category', ['class' => 'control-label'])); ?>

            <?php echo e(Form::select('category_id[]', \App\Category::selection('Select Category'),$survey->categories ?? null , ['class' => 'form-control','multiple','size'=>20])); ?>

            <?php if($errors->has('category_id')): ?>
                <div class="error-message"><?php echo e($errors->first('category_id')); ?></div>
            <?php endif; ?>
        </div>


        <div class="form-group">
            <button class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
        </div>
    </div>
</div>