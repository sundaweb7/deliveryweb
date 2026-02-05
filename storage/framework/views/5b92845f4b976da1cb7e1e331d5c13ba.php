

<?php $__env->startSection('driver-content'); ?>
    <h2>Orders</h2>
    <table id="driver-orders-table" class="display" style="width:100%"><thead><tr><th>ID</th><th>Order No</th><th>Customer</th><th>Status</th><th>Fee</th><th>Aksi</th></tr></thead></table>

    <script>
    document.addEventListener('DOMContentLoaded', function(){
        const table = initDataTable('#driver-orders-table', '<?php echo e(route('driver.orders.data')); ?>', [
            { data:0 },{ data:1 },{ data:2 },{ data:3 },{ data:4 },{ data:5, orderable:false, searchable:false }
        ]);

        $('#driver-orders-table').on('click', '.btn-view', function(){
            let id = $(this).data('id');
            $.get('<?php echo e(url('admin/driver/orders')); ?>/'+id, function(res){
                alert('Order '+res.order_no+' - Status: '+res.status);
            });
        });

        $('#driver-orders-table').on('click', '.btn-action', function(){
            let id = $(this).data('id');
            if (!confirm('Mark delivered?')) return;
            $.post('<?php echo e(url('admin/driver/orders')); ?>/'+id+'/mark-delivered', {}, function(){ table.ajax.reload(); alert('Marked delivered'); });
        });
    });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('driver.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\delivery\resources\views/driver/orders/index.blade.php ENDPATH**/ ?>