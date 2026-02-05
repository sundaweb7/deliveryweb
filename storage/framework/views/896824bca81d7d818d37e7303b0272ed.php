
<?php $__env->startSection('title','Wallet'); ?>
<?php $__env->startSection('content'); ?>
    <h1 class="page-title">Wallet</h1>
    <div class="card card-white">
        <div class="card-body">
            <h3>Rp <?php echo e(number_format($wallet->balance ?? 0,0,',','.')); ?></h3>
            <div>Balance</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h4>Recent Histories</h4>
            <ul>
                <?php $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($h->type); ?>: Rp <?php echo e(number_format($h->amount,0,',','.')); ?> (<?php echo e($h->created_at); ?>)</li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('mitra.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\delivery\resources\views/mitra/wallet/index.blade.php ENDPATH**/ ?>