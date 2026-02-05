
<?php $__env->startSection('title','Orders'); ?>
<?php $__env->startSection('content'); ?>
    <h1 class="page-title">Orders</h1>
    <table id="order-table" class="display" style="width:100%"><thead><tr><th>ID</th><th>Customer</th><th>Status</th><th>Total</th><th>Created</th><th>Aksi</th></tr></thead></table>

<script>
document.addEventListener('DOMContentLoaded', function(){
    initDataTable('#order-table','<?php echo e(route('mitra.orders.data')); ?>',[{data:0},{data:1},{data:2},{data:3},{data:4},{data:5,orderable:false,searchable:false}]);
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('mitra.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\delivery\resources\views/mitra/orders/index.blade.php ENDPATH**/ ?>