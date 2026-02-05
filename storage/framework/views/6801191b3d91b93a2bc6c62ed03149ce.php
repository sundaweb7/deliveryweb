
<?php $__env->startSection('title','Settings'); ?>
<?php $__env->startSection('content'); ?>
    <h1 class="page-title">Settings</h1>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label>Price per km (IDR)</label>
            <input type="number" name="price_per_km" class="form-control" value="<?php echo e($settings['price_per_km'] ?? ''); ?>">
            <?php $__errorArgs = ['price_per_km'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label>Admin Fee Percent (%)</label>
            <input type="number" name="admin_fee_percent" class="form-control" value="<?php echo e($settings['admin_fee_percent'] ?? ''); ?>">
            <?php $__errorArgs = ['admin_fee_percent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label>Max Radius (km)</label>
            <input type="number" step="0.1" name="max_radius_km" class="form-control" value="<?php echo e($settings['max_radius_km'] ?? ''); ?>">
            <?php $__errorArgs = ['max_radius_km'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <hr>
        <h4>WA Provider (Mpedia) ⚙️</h4>
        <div class="form-group">
            <label>MPEDIA API KEY</label>
            <input type="text" name="mpedia_api_key" class="form-control" value="<?php echo e($settings['mpedia_api_key'] ?? env('MPEDIA_API_KEY')); ?>">
            <?php $__errorArgs = ['mpedia_api_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="form-group">
            <label>MPEDIA SENDER</label>
            <input type="text" name="mpedia_sender" class="form-control" value="<?php echo e($settings['mpedia_sender'] ?? env('MPEDIA_SENDER')); ?>">
            <?php $__errorArgs = ['mpedia_sender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <hr>
        <h4>Duitku Payment Gateway ⚙️</h4>
        <div class="form-group">
            <label>Merchant Code</label>
            <input type="text" name="duitku_merchant_code" class="form-control" value="<?php echo e($settings['duitku_merchant_code'] ?? env('DUITKU_MERCHANT_CODE')); ?>">
            <?php $__errorArgs = ['duitku_merchant_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="form-group">
            <label>Merchant Key</label>
            <input type="text" name="duitku_merchant_key" class="form-control" value="<?php echo e($settings['duitku_merchant_key'] ?? env('DUITKU_MERCHANT_KEY')); ?>">
            <?php $__errorArgs = ['duitku_merchant_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="form-group">
            <label>API URL</label>
            <input type="text" name="duitku_api_url" class="form-control" value="<?php echo e($settings['duitku_api_url'] ?? env('DUITKU_API_URL')); ?>">
            <?php $__errorArgs = ['duitku_api_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="form-group">
            <label>Test Mode</label>
            <div class="form-check">
                <input type="checkbox" name="duitku_is_test_mode" value="1" class="form-check-input" id="duitku-test-mode" <?php echo e((isset($settings['duitku_is_test_mode']) ? $settings['duitku_is_test_mode'] == '1' : (env('DUITKU_IS_TEST_MODE') ? true : false)) ? 'checked' : ''); ?>>
                <label class="form-check-label" for="duitku-test-mode">Enable test mode</label>
            </div>
            <?php $__errorArgs = ['duitku_is_test_mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <button class="btn btn-primary">Save Settings</button>
    </form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\delivery\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>