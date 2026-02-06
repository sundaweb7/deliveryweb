<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title','Driver Area'); ?></title>
    <link rel="icon" href="/images/iconAD.png">
    <!-- Theme CSS -->
    <link rel="stylesheet" href="/vendor/theme/Gxon_v1_22-Dec-2025/demo/public/assets/css/styles.css">
    <link rel="stylesheet" href="/css/admin-custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<header class="admin-topbar">
    <div class="brand">
        <img src="/images/logoAd.png" alt="Logo" style="height:40px">
        <button class="btn btn-icon sidebar-toggle" aria-label="Toggle sidebar"><i class="fa fa-bars"></i></button>
    </div>
    <?php if (! (request()->routeIs('driver.login'))): ?>
    <nav class="top-actions">
        <form action="<?php echo e(route('driver.logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button class="btn btn-outline" type="submit">Logout</button>
        </form>
    </nav>
    <?php endif; ?>
</header>
<div class="admin-wrapper">
    <?php if (! (request()->routeIs('driver.login'))): ?>
    <aside class="admin-sidebar">
        <ul class="nav">
            <li><a href="<?php echo e(route('driver.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('driver.dashboard') ? 'active' : ''); ?>"><i class="fa fa-tachometer-alt"></i><span class="nav-label">Dashboard</span></a></li>
            <li><a href="<?php echo e(route('driver.orders.index')); ?>" class="nav-link <?php echo e(request()->routeIs('driver.orders.*') ? 'active' : ''); ?>"><i class="fa fa-shopping-basket"></i><span class="nav-label">Orders</span></a></li>
            <li><a href="<?php echo e(route('driver.wallet.index')); ?>" class="nav-link <?php echo e(request()->routeIs('driver.wallet.*') ? 'active' : ''); ?>"><i class="fa fa-wallet"></i><span class="nav-label">Wallet</span></a></li>
            <li><a href="<?php echo e(route('driver.settings.index')); ?>" class="nav-link <?php echo e(request()->routeIs('driver.settings.*') ? 'active' : ''); ?>"><i class="fa fa-cog"></i><span class="nav-label">Settings</span></a></li>
        </ul>
    </aside>
    <?php endif; ?>

    <main class="admin-content">
        <div class="container">
            <?php echo $__env->yieldContent('driver-content'); ?>
        </div>
    </main>
</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="/js/admin-crud.js"></script>
    <script src="/vendor/theme/Gxon_v1_22-Dec-2025/demo/public/assets/js/main.js"></script>
    <script>
        // Toggle nav-group submenu on click
        document.addEventListener('DOMContentLoaded', function(){
            document.querySelectorAll('.admin-sidebar .nav-group > .nav-link').forEach(function(el){
                el.addEventListener('click', function(e){
                    e.preventDefault();
                    const parent = this.parentElement;
                    parent.classList.toggle('open');
                });
            });
        });
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\deliveryweb\resources\views/driver/layouts/app.blade.php ENDPATH**/ ?>