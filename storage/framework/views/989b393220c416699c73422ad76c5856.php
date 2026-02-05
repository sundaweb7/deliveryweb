<div class="login-tabs">
    <a href="<?php echo e(route('admin.login')); ?>" class="tab tab-admin <?php echo e(request()->routeIs('admin.login') ? 'active' : ''); ?>" aria-label="Login Admin">
        <span>Login Admin</span>
    </a>
    <a href="<?php echo e(route('driver.login')); ?>" class="tab tab-driver <?php echo e(request()->routeIs('driver.login') ? 'active' : ''); ?>" aria-label="Login Driver">
        <span>Login Driver</span>
    </a>
    <a href="<?php echo e(route('mitra.login')); ?>" class="tab tab-mitra <?php echo e(request()->routeIs('mitra.login') ? 'active' : ''); ?>" aria-label="Login Mitra">
        <span>Login Mitra</span>
    </a>
    <a href="<?php echo e(route('customer.login')); ?>" class="tab tab-customer <?php echo e(request()->routeIs('customer.login') ? 'active' : ''); ?>" aria-label="Login Customer">
        <span>Login Customer</span>
    </a>
</div><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\delivery\resources\views/auth/login-tabs.blade.php ENDPATH**/ ?>