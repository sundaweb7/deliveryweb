

<?php $__env->startSection('title','Mitra Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-title">Mitra Dashboard</div>
    <div class="grid">
        <div class="card card-pink">
            <div class="card-body">
                <h3><?php echo e($productCount); ?></h3>
                <div>Products</div>
            </div>
        </div>
        <div class="card card-blue">
            <div class="card-body">
                <h3><?php echo e($orderCount); ?></h3>
                <div>Orders</div>
            </div>
        </div>
        <div class="card card-white">
            <div class="card-body">
                <h3>Rp <?php echo e(number_format($wallet,0,',','.')); ?></h3>
                <div>Wallet Balance</div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('mitra.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\delivery\resources\views/mitra/dashboard.blade.php ENDPATH**/ ?>