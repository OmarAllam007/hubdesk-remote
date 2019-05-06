
<?php if($items->lastPage() > 1): ?>
    <?php
    $mod = 8;
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $items */
    $page = $items->currentPage();

    if ($items->lastPage() < $mod) {
        $start = 1;
        $last = $items->lastPage();
    } elseif ($page < $mod) {
        $start = 1;
        $last = $mod;
    } elseif ($page > $items->lastPage() - $mod) {
        $start = $items->lastPage() - $mod + 1;
        $last = $items->lastPage();
    } else {
        $start = $page - $mod / 2 + 1;
        $last = $page + $mod / 2;
    }

    $prefix= Request::fullUrl();
    if (strpos($prefix, '?') === false) {
        $prefix .= '?';
    } else {
        $prefix .= '&';
    }
    ?>

    <div class="text-center">
        <ul class="pagination">
            <?php if($page > 1): ?>
                <li>
                    <a href="<?php echo e($prefix); ?>page=<?php echo e($page - 1); ?>"><i class="fa fa-chevron-left fa-fw"></i></a>
                </li>
            <?php endif; ?>
            <?php for($i = $start; $i <= $last; ++$i): ?>
                <li <?php if($page == $i): ?>class="active"<?php endif; ?>>
                    <a href="<?php echo e($prefix); ?>page=<?php echo e($i); ?>"><?php echo e($i); ?></a>
                </li>
            <?php endfor; ?>
            <?php if($page < $items->lastPage()): ?>
                <li>
                    <a href="<?php echo e($prefix); ?>page=<?php echo e($page + 1); ?>"><i class="fa fa-chevron-right fa-fw"></i></a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
<?php endif; ?>