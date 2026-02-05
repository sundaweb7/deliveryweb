

<?php $__env->startSection('customer-content'); ?>
    <h2>Dashboard</h2>
    <div class="row">
        <div class="col"><div class="card">Orders: <strong><?php echo e($ordersCount); ?></strong></div></div>
        <div class="col"><div class="card">Pending: <strong><?php echo e($pending); ?></strong></div></div>
        <div class="col"><div class="card">Delivered: <strong><?php echo e($delivered); ?></strong></div></div>
        <div class="col"><div class="card">Wallet: <strong><?php echo e($wallet ? $wallet->balance : 0); ?></strong></div></div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('customer.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\delivery\resources\views/customer/dashboard.blade.php ENDPATH**/ ?>