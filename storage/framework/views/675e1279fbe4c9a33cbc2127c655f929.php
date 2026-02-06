<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Admin Panel'); ?></title>
    <link rel="icon" href="/images/iconAD.png">
    <!-- Theme CSS -->
    <link rel="stylesheet" href="/vendor/theme/Gxon_v1_22-Dec-2025/demo/public/assets/css/styles.css">
    <link rel="stylesheet" href="/css/admin-custom.css">
    <!-- Font Awesome CDN for icons (reliable, free version) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<header class="admin-topbar">
    <div class="brand">
        <img src="/images/logoAd.png" alt="Logo" style="height:40px">
        <button class="btn btn-icon sidebar-toggle" aria-label="Toggle sidebar"><i class="fa fa-bars"></i></button>
    </div>
    <nav class="top-actions">
        <form action="<?php echo e(route('admin.logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button class="btn btn-outline" type="submit">Logout</button>
        </form>
    </nav>
</header>
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <ul class="nav">
            <li><a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>"><i class="fa fa-tachometer-alt"></i><span class="nav-label">Dashboard</span></a></li>
            <li><a href="<?php echo e(route('admin.users.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>"><i class="fa fa-users"></i><span class="nav-label">User AD</span></a></li>
            <li><a href="<?php echo e(route('admin.mitras.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.mitras.*') ? 'active' : ''); ?>"><i class="fa fa-store"></i><span class="nav-label">Mitra AD</span></a></li>
            <li><a href="<?php echo e(route('admin.drivers.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.drivers.*') ? 'active' : ''); ?>"><i class="fa fa-motorcycle"></i><span class="nav-label">Driver AD</span></a></li>
            <li><a href="<?php echo e(route('driver.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('driver.*') ? 'active' : ''); ?>"><i class="fa fa-motorcycle"></i><span class="nav-label">Driver Area</span></a></li>
            <li><a href="<?php echo e(route('customer.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('customer.*') ? 'active' : ''); ?>"><i class="fa fa-user"></i><span class="nav-label">Customer Area</span></a></li>
            <li><a href="<?php echo e(route('admin.products.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.products.*') ? 'active' : ''); ?>"><i class="fa fa-box"></i><span class="nav-label">Products</span></a></li>
            <li><a href="<?php echo e(route('admin.categories.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.categories.*') ? 'active' : ''); ?>"><i class="fa fa-tags"></i><span class="nav-label">Categories</span></a></li>
            <li><a href="<?php echo e(route('admin.orders.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.orders.*') ? 'active' : ''); ?>"><i class="fa fa-shopping-basket"></i><span class="nav-label">Orders</span></a></li>
            <li><a href="<?php echo e(route('admin.payments.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.payments.*') ? 'active' : ''); ?>"><i class="fa fa-credit-card"></i><span class="nav-label">Payments</span></a></li>
            <li><a href="<?php echo e(route('admin.wallets.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.wallets.*') ? 'active' : ''); ?>"><i class="fa fa-wallet"></i><span class="nav-label">Wallets</span></a></li>
            <li><a href="<?php echo e(route('admin.driver_locations.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.driver_locations.*') ? 'active' : ''); ?>"><i class="fa fa-map-marker-alt"></i><span class="nav-label">Driver Tracking</span></a></li>
            <li><a href="<?php echo e(route('admin.profit.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.profit.*') ? 'active' : ''); ?>"><i class="fa fa-chart-line"></i><span class="nav-label">Admin Profit</span></a></li>
            <li class="nav-group <?php echo e(request()->routeIs('admin.promos.*') || request()->routeIs('admin.banners.*') || request()->routeIs('admin.wa_broadcasts.*') ? 'open' : ''); ?>">
                <a href="#" class="nav-link"><i class="fa fa-bullhorn"></i><span class="nav-label">Marketing</span></a>
                <ul class="nav-sub">
                    <li><a href="<?php echo e(route('admin.promos.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.promos.*') ? 'active' : ''); ?>">Promos</a></li>
                    <li><a href="<?php echo e(route('admin.banners.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.banners.*') ? 'active' : ''); ?>">Banners</a></li>
                    <li><a href="<?php echo e(route('admin.wa_broadcasts.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.wa_broadcasts.*') ? 'active' : ''); ?>">WA Broadcast</a></li>
                </ul>
            </li>
            <li><a href="<?php echo e(route('admin.system_logs.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.system_logs.*') ? 'active' : ''); ?>"><i class="fa fa-history"></i><span class="nav-label">System Logs</span></a></li>
            <li><a href="<?php echo e(route('admin.settings.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.settings.*') ? 'active' : ''); ?>"><i class="fa fa-cog"></i><span class="nav-label">Settings</span></a></li>
        </ul>
    </aside>

    <main class="admin-content">
        <div class="container">
            <?php echo $__env->yieldContent('content'); ?>
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
</html><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\deliveryweb\resources\views/admin/layouts/app.blade.php ENDPATH**/ ?>