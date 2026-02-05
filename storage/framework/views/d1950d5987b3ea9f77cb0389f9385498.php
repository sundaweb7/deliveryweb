

<?php $__env->startSection('driver-content'); ?>
    <h2>Dashboard</h2>
    <div class="row">
        <div class="col"><div class="card">Assigned: <strong><?php echo e($assigned); ?></strong></div></div>
        <div class="col"><div class="card">On Delivery: <strong><?php echo e($onDelivery); ?></strong></div></div>
        <div class="col"><div class="card">Delivered: <strong><?php echo e($delivered); ?></strong></div></div>
        <div class="col"><div class="card">Wallet: <strong><?php echo e($wallet ? $wallet->balance : 0); ?></strong></div></div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('driver.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\delivery\resources\views/driver/dashboard.blade.php ENDPATH**/ ?>