<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <link rel="stylesheet" href="/vendor/theme/Gxon_v1_22-Dec-2025/demo/public/assets/css/styles.css">
    <link rel="stylesheet" href="/css/admin-custom.css">
</head>
<body class="bg-white">
<div class="login-container">
    <div class="login-box">
        <div class="logo text-center">
            <img src="/images/logoAd.png" alt="Logo" style="height:80px">
        </div>

        <?php echo $__env->make('auth.login-tabs', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger"><?php echo e($errors->first()); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('admin.login.post')); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary btn-block" type="submit">Login</button>
        </form>
    </div>
</div>
</body>
</html><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\delivery\resources\views/admin/auth/login.blade.php ENDPATH**/ ?>