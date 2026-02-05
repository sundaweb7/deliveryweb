
<?php $__env->startSection('title','Settings'); ?>
<?php $__env->startSection('content'); ?>
    <h1 class="page-title">Settings</h1>
    <form method="POST" action="<?php echo e(route('mitra.settings.update')); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-group"><label>Name</label><input name="name" value="<?php echo e($user->name); ?>" class="form-control"></div>
        <div class="form-group"><label>Phone</label><input name="phone" value="<?php echo e($user->phone); ?>" class="form-control"></div>
        <div class="form-group"><label>New Password (leave blank to keep)</label><input name="password" class="form-control" type="password"></div>
        <div style="margin-top:12px"><button class="btn" type="submit">Save</button></div>
        <?php if(session('success')): ?><div style="color:green;margin-top:8px"><?php echo e(session('success')); ?></div><?php endif; ?>
    </form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('mitra.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\delivery\resources\views/mitra/settings/index.blade.php ENDPATH**/ ?>