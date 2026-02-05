
<?php $__env->startSection('title','Categories'); ?>
<?php $__env->startSection('content'); ?>
    <h1 class="page-title">Categories</h1>
    <div class="mb-3"><button id="btn-add-category" class="btn">Tambah Category</button></div>
    <table id="categories-table" class="display" style="width:100%"><thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Status</th><th>Aksi</th></tr></thead></table>

    <div class="modal" id="categoryModal"><div class="modal-dialog"><div class="modal-content"><form class="ajax-form" id="category-form" data-refresh-table="#categories-table"><div class="modal-header"><h5 class="modal-title">Category</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><input type="hidden" name="id" id="category-id"><div class="form-group"><label>Name</label><input name="name" id="category-name" class="form-control"></div><div class="form-group"><label>Description</label><input name="description" id="category-desc" class="form-control"></div><div class="form-group"><label>Status</label><select name="status" id="category-status" class="form-control"><option value="active">Active</option><option value="inactive">Inactive</option></select></div></div><div class="modal-footer"><button type="submit" class="btn">Simpan</button><button type="button" class="btn btn-outline" data-dismiss="modal">Batal</button></div></form></div></div></div>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        const table = initDataTable('#categories-table','<?php echo e(route('admin.categories.data')); ?>',[
            { data:0 },{ data:1 },{ data:2 },{ data:3 },{ data:4, orderable:false, searchable:false }
        ]);

        $('#btn-add-category').on('click', function(){ $('#category-form').attr('data-url','<?php echo e(url('admin/categories')); ?>').attr('data-method','POST'); $('#category-form')[0].reset(); $('#category-id').val(''); $('#categoryModal').modal('show'); });

        $('#categories-table').on('click','.btn-delete', function(){ if(!confirm('Hapus category?')) return; let id=$(this).data('id'); $.ajax({url:'<?php echo e(url('admin/categories')); ?>/'+id, method:'DELETE', headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}, success:function(){table.ajax.reload()}});
        });

        $('#categories-table').on('click','.btn-edit', function(){ let id=$(this).data('id'); $.get('<?php echo e(url('admin/categories')); ?>/'+id, function(res){ $('#category-form').attr('data-url','<?php echo e(url('admin/categories')); ?>/'+id).attr('data-method','PUT'); $('#category-id').val(res.id); $('#category-name').val(res.name); $('#category-desc').val(res.description); $('#category-status').val(res.status); $('#categoryModal').modal('show'); }); });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\delivery\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>