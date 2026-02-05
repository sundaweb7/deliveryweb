

<?php $__env->startSection('title','Driver Login'); ?>

<?php $__env->startSection('driver-content'); ?>
    <div style="max-width:420px;margin:40px auto">
        <div class="card">
            <div class="card-body">
                <div class="logo text-center"><img src="/images/logoAd.png" alt="Logo" style="height:80px;margin-bottom:12px"></div>
                <?php echo $__env->make('auth.login-tabs', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php if($errors->any()): ?><div class="alert alert-danger"><?php echo e($errors->first()); ?></div><?php endif; ?>
                <h3>Driver Login</h3>
                <form method="POST" action="<?php echo e(route('driver.login.post')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="form-group"><label>Email</label><input name="email" type="email" class="form-control" required></div>
                    <div class="form-group"><label>Password</label><input name="password" type="password" class="form-control" required></div>
                    <button class="btn">Login</button>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('driver.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\delivery\resources\views/driver/auth/login.blade.php ENDPATH**/ ?>