<div id="BusinessRoles">
    <div class="row">
        <div class="col-md-6">
            <?php echo e(csrf_field()); ?>


            <?php echo e(Form::hidden('id')); ?>

            <div class="form-group <?php echo e($errors->has('code')? 'has-errors' : ''); ?>">
                <?php echo e(Form::label('code', 'Code', ['class' => 'control-label'])); ?>

                <?php echo e(Form::text('code', null, ['class' => 'form-control'])); ?>

                <?php if($errors->has('code')): ?>
                    <div class="error-message"><?php echo e($errors->first('code')); ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group <?php echo e($errors->has('name')? 'has-errors' : ''); ?>">
                <?php echo e(Form::label('name', 'Name', ['class' => 'control-label'])); ?>

                <?php echo e(Form::text('name', null, ['class' => 'form-control'])); ?>

                <?php if($errors->has('name')): ?>
                    <div class="error-message"><?php echo e($errors->first('name')); ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group <?php echo e($errors->has('location_id')? 'has-errors' : ''); ?>">
                <?php echo e(Form::label('location_id', 'Default Location', ['class' => 'control-label'])); ?>

                <?php echo e(Form::select('location_id', \App\Location::selection('Select Location'), null, ['class' => 'form-control'])); ?>

                <?php if($errors->has('location_id')): ?>
                    <div class="error-message"><?php echo e($errors->first('location_id')); ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group <?php echo e($errors->has('logo_img')? 'has-errors' : ''); ?>">
                <?php echo e(Form::label('logo_img', 'Logo', ['class' => 'control-label'])); ?>

                <?php echo e(Form::input('file','logo_img', null, ['class' => 'form-control'])); ?>

                <?php if($errors->has('logo_img')): ?>
                    <div class="error-message"><?php echo e($errors->first('logo_img')); ?></div>
                <?php endif; ?>
            </div>
            <fieldset>
                <legend>Roles</legend>
                <roles :users="<?php echo e(\App\User::orderBy('name')->get()); ?>" :roles="<?php echo e(\App\Role::all()); ?>"
                       :bu_id="<?php echo e(isset($business_unit) ? $business_unit : 0); ?>"></roles>
            </fieldset>

            <div class="form-group">
                <button class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo e(asset('/js/roles.js')); ?>"></script>
