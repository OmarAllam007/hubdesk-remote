<?php echo e(Form::model($ticket, ['route' => ['ticket.reassign', $ticket], 'class' => 'modal fade', 'id' => 'AssignForm'])); ?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo e(t('Assign Ticket')); ?></h4>
        </div>

        <div class="modal-body" id="TicketForm">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group <?php echo e($errors->first('group_id', 'has-error')); ?>">
                        <?php echo e(Form::label('group_id', t('Group'), ['class' => 'control-label'])); ?>

                        <?php echo e(Form::select('group_id', App\Group::selection('Select Group'),null, ['class' => 'form-control','v-model'=>'group'])); ?>

                        <?php echo $errors->first('group_id', '<div class="help-block">:message</div>'); ?>

                    </div>

                    <div class="form-group ">
                        <?php echo e(Form::label('technician_id', t('Technician'), ['class' => 'control-label'])); ?>

                        <select  class="form-control" name="technician_id" id="technician_id" v-model="technician_id">
                            <option value="">Select Technician</option>
                            <option v-for="tech in technicians" :value="tech.id"> {{tech.name}}</option>
                        </select>
                        <?php if($errors->has('technician_id')): ?>
                            <div class="error-message"><?php echo e($errors->first('technician_id')); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group <?php echo e($errors->has('category_id')? 'has-error' : ''); ?>">
                        <?php echo e(Form::label('category_id', t('Category'), ['class' => 'control-label'])); ?>

                        <?php echo e(Form::select('category_id', App\Category::selection('Select Category'),null, ['class' => 'form-control',  'v-model' => 'category'])); ?>

                        <?php if($errors->has('category_id')): ?>
                            <div class="error-message"><?php echo e($errors->first('category_id')); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group <?php echo e($errors->first('subcategory', 'has-error')); ?>">
                        <?php echo e(Form::label('subcategory_id', t('Subcategory'), ['class' => 'control-label'])); ?>


                        <select class="form-control" name="subcategory_id" id="subcategory_id" v-model="subcategory">
                            <option value="">Select Subcategory</option>
                            <option v-for="subcat in subcategories" :value="subcat.id" v-text="subcat.name"></option>
                        </select>

                        <?php if($errors->has('subcategory_id')): ?>
                            <div class="error-message"><?php echo e($errors->first('subcategory_id')); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group  <?php echo e($errors->has('item_id')? 'has-error' : ''); ?>">
                        <?php echo e(Form::label('item_id', t('Item'), ['class' => 'control-label'])); ?>

                        <select class="form-control" name="item_id" id="item_id" v-model="item">
                            <option value="">Select Item</option>
                            <option v-for="(item, id) in items" :value="id" v-text="item"></option>
                        </select>
                        <?php if($errors->has('item_id')): ?>
                            <div class="error-message"><?php echo e($errors->first('item_id')); ?></div>
                        <?php endif; ?>
                    </div>

                    
                        
                            
                            
                            
                        
                    

                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-mail-forward"></i> <?php echo e(t('Assign')); ?></button>
        </div>
    </div>
</div>
<?php echo e(Form::close()); ?>

<?php $__env->startSection('javascript'); ?>
    <script>
        var category = '<?php echo e(Form::getValueAttribute('category_id') ?? $ticket->category_id); ?>';
        var subcategory = '<?php echo e(Form::getValueAttribute('subcategory_id') ?? $ticket->subcategory_id); ?>';
        var item = '<?php echo e(Form::getValueAttribute('item_id') ?? $ticket->item_id); ?>';
        var group = '<?php echo e(Form::getValueAttribute('group_id') ?? $ticket->group_id); ?>'
        var technician_id = '<?php echo e(Form::getValueAttribute('technician_id') ?? $ticket->technician_id); ?>'
    </script>
    <script src="<?php echo e(asset('/js/ticket-form.js')); ?>"></script>
    <script src="<?php echo e(asset('/js/tinymce/tinymce.min.js')); ?>"></script>
<?php $__env->appendSection(); ?>