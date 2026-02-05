

<?php $__env->startSection('customer-content'); ?>
    <h2>Wallet</h2>
    <div class="card">Balance: <strong><?php echo e($wallet ? $wallet->balance : 0); ?></strong></div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('customer.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\delivery\resources\views/customer/wallet/index.blade.php ENDPATH**/ ?>