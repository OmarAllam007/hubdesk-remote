<div id="SearchForm" class="<?php echo e(!Session::has('ticket.filter') ? 'collapse' : ''); ?>">
    <?php echo e(Form::open(['route' => 'ticket.filter'])); ?>


    <criteria :criterions="<?php echo e(json_encode(session('ticket.filter'))); ?>"></criteria>

    <div class="clearfix">
        <div class="btn-toolbar pull-right">
            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
            <a href="<?php echo e(route('ticket.clear')); ?>" class="btn btn-default"><i class="fa fa-remove"></i> Clear</a>
        </div>
    </div>

    <hr>

    <?php echo e(Form::close()); ?>

</div>


