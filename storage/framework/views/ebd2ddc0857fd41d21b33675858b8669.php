
<?php $__env->startSection('title','Dashboard'); ?>
<?php $__env->startSection('content'); ?>
    <h1 class="page-title">Dashboard</h1>

    <div class="grid">
        <div class="card card-pink">
            <div class="card-body">
                <h3><?php echo e($todayOrders); ?></h3>
                <p>Orders Hari Ini</p>
            </div>
        </div>

        <div class="card card-blue">
            <div class="card-body">
                <h3><?php echo e(number_format($totalIncome)); ?></h3>
                <p>Total Income</p>
            </div>
        </div>

        <div class="card card-white">
            <div class="card-body">
                <h3><?php echo e(number_format($adminProfit)); ?></h3>
                <p>Admin Profit</p>
            </div>
        </div>

        <div class="card card-lightpink">
            <div class="card-body">
                <h3><?php echo e($driversActive); ?></h3>
                <p>Driver Aktif</p>
            </div>
        </div>

        <div class="card card-green">
            <div class="card-body">
                <h3><?php echo e($totalMitra); ?></h3>
                <p>Total Mitra</p>
            </div>
        </div>

        <div class="card card-orange">
            <div class="card-body">
                <h3><?php echo e($totalUsers); ?></h3>
                <p>Total User</p>
            </div>
        </div>

        <div class="card card-purple">
            <div class="card-body">
                <h3><?php echo e($totalProducts); ?></h3>
                <p>Total Produk</p>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\deliveryweb\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>