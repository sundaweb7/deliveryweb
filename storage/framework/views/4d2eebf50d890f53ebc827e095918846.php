<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Mitra Panel'); ?></title>
    <link rel="icon" href="/images/iconAD.png">
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
    <?php if (! (request()->routeIs('mitra.login'))): ?>
    <nav class="top-actions">
        <form action="<?php echo e(route('mitra.logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button class="btn btn-outline" type="submit">Logout</button>
        </form>
    </nav>
    <?php endif; ?>
</header>
<div class="admin-wrapper">
    <?php if (! (request()->routeIs('mitra.login'))): ?>
    <aside class="admin-sidebar">
        <ul class="nav">
            <li><a href="<?php echo e(route('mitra.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('mitra.dashboard') ? 'active' : ''); ?>"><i class="fa fa-tachometer-alt"></i><span class="nav-label">Dashboard</span></a></li>
            <li><a href="<?php echo e(route('mitra.products.index')); ?>" class="nav-link <?php echo e(request()->routeIs('mitra.products.*') ? 'active' : ''); ?>"><i class="fa fa-box"></i><span class="nav-label">My Products</span></a></li>
            <li><a href="<?php echo e(route('mitra.orders.index')); ?>" class="nav-link <?php echo e(request()->routeIs('mitra.orders.*') ? 'active' : ''); ?>"><i class="fa fa-shopping-basket"></i><span class="nav-label">Orders</span></a></li>
            <li><a href="<?php echo e(route('mitra.wallet.index')); ?>" class="nav-link <?php echo e(request()->routeIs('mitra.wallet.*') ? 'active' : ''); ?>"><i class="fa fa-wallet"></i><span class="nav-label">Wallet</span></a></li>
            <li><a href="<?php echo e(route('mitra.settings.index')); ?>" class="nav-link <?php echo e(request()->routeIs('mitra.settings.*') ? 'active' : ''); ?>"><i class="fa fa-cog"></i><span class="nav-label">Settings</span></a></li>
        </ul>
    </aside>
    <?php endif; ?>

    <main class="admin-content">
        <div class="container">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="/js/admin-crud.js"></script>
</body>
</html><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\delivery\resources\views/mitra/layouts/app.blade.php ENDPATH**/ ?>