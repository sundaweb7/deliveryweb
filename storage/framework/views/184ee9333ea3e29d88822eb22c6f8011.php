
<?php $__env->startSection('title','Drivers'); ?>
<?php $__env->startSection('content'); ?>
    <h1 class="page-title">Drivers</h1>
    <div class="mb-3"><button id="btn-add-driver" class="btn">Tambah Driver</button></div>
    <table id="driver-table" class="display" style="width:100%"><thead><tr><th>ID</th><th>Name</th><th>Vehicle</th><th>Number</th><th>WA Contact</th><th>Active</th><th>Aksi</th></tr></thead></table>

    <div class="modal" id="driverModal"><div class="modal-dialog"><div class="modal-content"><form class="ajax-form" id="driver-form" data-refresh-table="#driver-table"><div class="modal-header"><h5 class="modal-title">Driver</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><input type="hidden" name="id" id="driver-id"><div class="form-group"><label>User</label><select name="user_id" id="driver-user_id" class="form-control"><?php $__currentLoopData = \App\Models\User::where('role','driver')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($u->id); ?>"><?php echo e($u->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group"><label>Name</label><input name="name" id="driver-name" class="form-control"></div>
                        <div class="form-group"><label>Address</label><input name="address" id="driver-address" class="form-control"></div>
                        <div class="form-group"><label>WA Contact</label><input name="wa_contact" id="driver-wa_contact" class="form-control" placeholder="6281xxxx (WhatsApp)"></div>
                        <div class="form-group"><label>Bank Account Number</label><input name="bank_account_number" id="driver-bank_account_number" class="form-control" placeholder="Nomor rekening"></div>
                        <div class="form-group"><label>Bank Name</label><input name="bank_name" id="driver-bank_name" class="form-control"></div>
                        <div class="form-group"><label>Bank Account Name</label><input name="bank_account_name" id="driver-bank_account_name" class="form-control"></div>
                        <div class="form-group"><label>Vehicle Type</label><input name="vehicle_type" id="driver-vehicle_type" class="form-control"></div><div class="form-group"><label>Vehicle Number</label><input name="vehicle_number" id="driver-vehicle_number" class="form-control"></div><div class="form-group"><label>Active</label><select name="is_active" id="driver-active" class="form-control"><option value="1">Yes</option><option value="0">No</option></select></div></div><div class="modal-footer"><button type="submit" class="btn">Simpan</button><button type="button" class="btn btn-outline" data-dismiss="modal">Batal</button></div></form></div></div></div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const table = initDataTable('#driver-table','<?php echo e(route('admin.drivers.data')); ?>',[
        { data:0 },{ data:1 },{ data:2 },{ data:3 },{ data:4 },{ data:5 },{ data:6,orderable:false,searchable:false }
    ]);

    $('#btn-add-driver').on('click', function(){
        $('#driver-form').attr('data-url','<?php echo e(url('admin/drivers')); ?>').attr('data-method','POST');
        $('#driver-form')[0].reset();
        $('#driverModal').modal('show');
    });

    $('#driver-table').on('click','.btn-delete', function(){if(!confirm('Hapus driver?')) return; let id=$(this).data('id'); $.ajax({url:'<?php echo e(url('admin/drivers')); ?>/'+id, method:'DELETE', headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}, success:function(){table.ajax.reload()}});
    });

    $('#driver-table').on('click','.btn-edit', function(){let id=$(this).data('id'); $.get('<?php echo e(url('admin/drivers')); ?>/'+id, function(res){$('#driver-form').attr('data-url','<?php echo e(url('admin/drivers')); ?>/'+id).attr('data-method','PUT'); $('#driver-user_id').val(res.user_id); $('#driver-name').val(res.name); $('#driver-address').val(res.address); $('#driver-wa_contact').val(res.wa_contact); $('#driver-bank_account_number').val(res.bank_account_number); $('#driver-bank_name').val(res.bank_name); $('#driver-bank_account_name').val(res.bank_account_name); $('#driver-vehicle_type').val(res.vehicle_type); $('#driver-vehicle_number').val(res.vehicle_number); $('#driver-active').val(res.is_active?1:0); $('#driverModal').modal('show');});});
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\delivery\resources\views/admin/drivers/index.blade.php ENDPATH**/ ?>