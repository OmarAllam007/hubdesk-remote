<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HubDesk</title>
    <link rel="shortcut icon" href="<?php echo e(asset('images/favicon.ico')); ?>">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mada:400,700">
    
    <link rel="stylesheet" href="<?php echo e(asset('/css/app.css')); ?>">

    <?php if(\Session::get('personlized-language-ar' . auth()->id(), config('app.locale')) == "ar"): ?>
        <link rel="stylesheet" href="<?php echo e(asset('/css/bootstrap-rtl.css')); ?>">
    <?php endif; ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>"/>
    <?php echo $__env->yieldContent('stylesheets'); ?>
    <style>
        ul.navbar > li:hover {
            background: #20639c !important;
            border-radius: 2px;
        }
    </style>

</head>
<body>

<header>
    <nav class="navbar navbar-default navbar-static-top navbar-style exto-bold" >
        <div class="container-fluid">
            <ul class="nav navbar-nav">
                <li>
                    <a href="<?php echo e(route('ticket.index')); ?>">
                        <i class="fa fa-life-ring"></i> HUBDESK</a>
                </li>
            </ul>
            <?php if(!\Auth::guest()): ?>
                <ul class="nav navbar-nav " >
                    <?php if(Auth::user()->isAdmin()): ?>
                        <li class="nav-item"><a href="<?php echo e(route('ticket.create')); ?>"><i class="fa fa-plus"></i> <?php echo e(t('New Ticket')); ?></a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a href="<?php echo e(route('ticket.index')); ?>"><i class="fa fa-ticket"></i> <?php echo e(t('Tickets')); ?></a></li>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reports')): ?>
                        <li class="nav-item"><a href="<?php echo e(url('/reports')); ?>"><i class="fa fa-bar-chart"></i> <?php echo e(t('Report')); ?></a></li>
                    <?php endif; ?>

                    <?php if(Auth::user()->isAdmin()): ?>
                        <li class="nav-item"><a href="<?php echo e(url('/admin')); ?>"><i class="fa fa-cogs"></i> <?php echo e(t('Admin')); ?></a></li>
                    <?php endif; ?>
                </ul>


                <ul class="nav navbar-nav">
                    <li class="dropdown nav-item">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-language"></i>
                            <i class="caret"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo e(route('site.changeLanguage','ar')); ?>"> <?php echo e(t('Arabic')); ?></a></li>
                            <li><a href="<?php echo e(route('site.changeLanguage','en')); ?>"> <?php echo e(t('English')); ?></a></li>
                        </ul>
                    </li>
                </ul>

                <ul class="nav navbar-nav
                    <?php if(\Session::get('personlized-language-ar' . \Auth::user()->id, \Config::get('app.locale'))=="ar"): ?>
                        navbar-left <?php else: ?> navbar-right
                    <?php endif; ?>">
                    <li class="dropdown nav-item">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                                    class="fa fa-user"></i> <?php echo e(Auth::user()->name); ?> <i class="caret"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo e(route('user.reset')); ?>"><i class="fa fa-unlock "></i>  <?php echo e(t('Reset Password')); ?></a></li>
                            <li><a href="<?php echo e(url('/logout')); ?>"><i class="fa fa-sign-out"></i> <?php echo e(t('Logout')); ?></a></li>
                        </ul>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </nav>
</header>

<div id="wrapper">
    <main class="container-fluid">
        <div class="row">
            <div class="title-bar">
                <div class="container-fluid title-container">
                    <?php echo $__env->yieldContent('header'); ?>
                </div>
            </div>
            <?php if (! empty(trim($__env->yieldContent('sidebar')))): ?>
                <?php echo $__env->yieldContent('sidebar'); ?>
            <?php endif; ?>
                <?php echo $__env->yieldContent('body'); ?>
        </div>
    </main>

    <footer>
        <div class="container-fluid">
            <div class="footer-container display-flex">
                
                
                
                
                
                

                <p class="text-mutedtext-right"><?php echo e(t('Copyright')); ?> &copy; <a
                            href="http://hubtech.sa">Hubtech</a> <?php echo e(date('Y')); ?></p>

                

            </div>
        </div>
    </footer>
</div>

<script src="<?php echo e(asset('/js/app.js')); ?>"></script>

<?php if(alert()->ready()): ?>
    <script>
        swal({
            title: "<?php echo alert()->message(); ?>",
            text: "<?php echo alert()->option('text'); ?>",
            type: "<?php echo alert()->type(); ?>",
            timer: 3000,
            showConfirmButton: false,
        });
    </script>
<?php endif; ?>
<?php echo $__env->yieldContent('javascript'); ?>
</body>
</html>
