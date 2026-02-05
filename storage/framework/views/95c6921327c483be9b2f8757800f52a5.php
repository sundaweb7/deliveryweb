
<?php $__env->startSection('title','Login Histories'); ?>
<?php $__env->startSection('content'); ?>
    <h1 class="page-title">Login Histories</h1>

    <table id="login-histories-table" class="display" style="width:100%">
        <thead><tr><th>ID</th><th>User</th><th>Role</th><th>IP</th><th>User Agent</th><th>Created</th></tr></thead>
    </table>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const table = initDataTable('#login-histories-table','<?php echo e(route('admin.login_histories.index')); ?>' + '/data', [
                { data: 0 },{ data: 1 },{ data: 2 },{ data: 3 },{ data: 4 },{ data: 5 }
            ]);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\delivery\resources\views/admin/login_histories/index.blade.php ENDPATH**/ ?>