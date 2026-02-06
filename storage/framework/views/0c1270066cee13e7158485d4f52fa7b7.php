
<?php $__env->startSection('title','Users'); ?>
<?php $__env->startSection('content'); ?>
    <h1 class="page-title">Users</h1>

    <div class="mb-3">
        <button id="btn-add-user" class="btn">Tambah User</button>
    </div>

    <table id="users-table" class="display" style="width:100%">
        <thead><tr><th>ID</th><th>Nama</th><th>Email</th><th>Phone</th><th>Role</th><th>Aksi</th></tr></thead>
    </table>

    <!-- Modal -->
    <div class="modal" id="userModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="ajax-form" id="user-form" data-refresh-table="#users-table">
                    <div class="modal-header"><h5 class="modal-title">User</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="user-id">
                        <div class="form-group"><label>Nama</label><input name="name" id="user-name" class="form-control"></div>
                        <div class="form-group"><label>Email</label><input name="email" id="user-email" class="form-control"></div>
                        <div class="form-group"><label>Phone</label><input name="phone" id="user-phone" class="form-control"></div>
                        <div class="form-group"><label>Role</label><select name="role" id="user-role" class="form-control"><option value="admin">Admin</option><option value="mitra">Mitra</option><option value="driver">Driver</option><option value="user">User</option></select></div>
                        <div class="form-group"><label>Password</label><input name="password" id="user-password" class="form-control" placeholder="Isi jika ingin mengubah/menetapkan password"></div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn">Simpan</button><button type="button" class="btn btn-outline" data-dismiss="modal">Batal</button></div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const table = initDataTable('#users-table', '<?php echo e(route('admin.users.index')); ?>' + '/data', [
                { data: 0 },
                { data: 1 },
                { data: 2 },
                { data: 3 },
                { data: 4 },
                { data: 5, orderable: false, searchable: false }
            ]);

            $('#btn-add-user').on('click', function(){
                $('#user-form').attr('data-url','<?php echo e(route('admin.users.index')); ?>').attr('data-method','POST');
                $('#user-form')[0].reset();
                $('#user-id').val('');
                $('#userModal').modal('show');
            });

            $('#users-table').on('click','.btn-delete', function(){
                if (!confirm('Hapus user?')) return;
                let id = $(this).data('id');
                $.ajax({url: '<?php echo e(url('admin/users')); ?>/'+id, method: 'DELETE', headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, success:function(){table.ajax.reload()}});
            });

            $('#users-table').on('click','.btn-edit', function(){
                let id = $(this).data('id');
                $.get('<?php echo e(url('admin/users')); ?>/'+id, function(res){
                    $('#user-form').attr('data-url','<?php echo e(url('admin/users')); ?>/'+id).attr('data-method','PUT');
                    $('#user-id').val(res.id);
                    $('#user-name').val(res.name);
                    $('#user-email').val(res.email);
                    $('#user-phone').val(res.phone);
                    $('#user-role').val(res.role);
                    $('#user-password').val('');
                    $('#userModal').modal('show');
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\deliveryweb\resources\views/admin/users/index.blade.php ENDPATH**/ ?>