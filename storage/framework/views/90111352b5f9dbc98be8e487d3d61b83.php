
<?php $__env->startSection('title','Banners'); ?>
<?php $__env->startSection('content'); ?>
    <h1 class="page-title">Banners</h1>

    <div class="mb-3">
        <button id="btn-add-banner" class="btn">Tambah Banner</button>
    </div>

    <table id="banners-table" class="display" style="width:100%">
        <thead><tr><th>ID</th><th>Title</th><th>Image</th><th>Link</th><th>Status</th><th>Aksi</th></tr></thead>
    </table>

    <!-- Modal -->
    <div class="modal" id="bannerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="ajax-form" id="banner-form" data-refresh-table="#banners-table" enctype="multipart/form-data">
                    <div class="modal-header"><h5 class="modal-title">Banner</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="banner-id">
                        <div class="form-group"><label>Title</label><input name="title" id="banner-title" class="form-control"></div>
                        <div class="form-group"><label>Link</label><input name="link" id="banner-link" class="form-control"></div>
                        <div class="form-group"><label>Image</label><input type="file" name="image" id="banner-image" class="form-control"></div>
                        <div class="form-group"><label>Status</label><select name="status" id="banner-status" class="form-control"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn">Simpan</button><button type="button" class="btn btn-outline" data-dismiss="modal">Batal</button></div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const table = initDataTable('#banners-table', '<?php echo e(route('admin.banners.index')); ?>' + '/data', [
                { data: 0 },{ data: 1 },{ data: 2 },{ data: 3 },{ data: 4 },{ data: 5, orderable:false, searchable:false }
            ]);

            $('#btn-add-banner').on('click', function(){
                $('#banner-form').attr('data-url','<?php echo e(url('admin/banners')); ?>').attr('data-method','POST');
                $('#banner-form')[0].reset();
                $('#banner-id').val('');
                $('#bannerModal').modal('show');
            });

            $('#banners-table').on('click','.btn-delete', function(){
                if (!confirm('Hapus banner?')) return;
                let id = $(this).data('id');
                $.ajax({url: '<?php echo e(url('admin/banners')); ?>/'+id, method: 'DELETE', headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, success:function(){table.ajax.reload()}});
            });

            $('#banners-table').on('click','.btn-edit', function(){
                let id = $(this).data('id');
                $.get('<?php echo e(url('admin/banners')); ?>/'+id, function(res){
                    $('#banner-form').attr('data-url','<?php echo e(url('admin/banners')); ?>/'+id).attr('data-method','PUT');
                    $('#banner-id').val(res.id);
                    $('#banner-title').val(res.title);
                    $('#banner-link').val(res.link);
                    $('#banner-status').val(res.status);
                    $('#bannerModal').modal('show');
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\delivery\resources\views/admin/banners/index.blade.php ENDPATH**/ ?>