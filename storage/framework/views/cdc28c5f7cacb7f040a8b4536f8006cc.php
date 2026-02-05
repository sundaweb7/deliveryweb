
<?php $__env->startSection('title','Wallets'); ?>
<?php $__env->startSection('content'); ?>
    <h1 class="page-title">Wallets</h1>

    <table id="wallets-table" class="display" style="width:100%">
        <thead><tr><th>ID</th><th>Owner</th><th>Balance</th><th>Aksi</th></tr></thead>
    </table>

    <!-- Histories Modal -->
    <div class="modal" id="walletModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Wallet Histories</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                <div class="modal-body" id="wallet-histories"></div>
                <div class="modal-footer"><button type="button" class="btn btn-outline" data-dismiss="modal">Close</button></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const table = initDataTable('#wallets-table', '<?php echo e(route('admin.wallets.index')); ?>' + '/data', [
                { data: 'id' },
                { data: function(row){ return (row.owner && row.owner.name) ? row.owner.name : (row.owner && row.owner.email ? row.owner.email : 'User#'+row.id); } },
                { data: function(row){ return (parseFloat(row.balance)||0).toFixed(2); } },
                { data: function(row){ return '<button class="btn btn-sm btn-hist" data-id="'+row.id+'">Histories</button>'; }, orderable:false, searchable:false }
            ]);

            $('#wallets-table').on('click','.btn-hist', function(){
                let id = $(this).data('id');
                $.get('<?php echo e(url('admin/wallets')); ?>/'+id+'/histories', function(res){
                    let html = '<ul>';
                    res.forEach(function(h){ html += '<li>['+h.created_at+'] '+h.type.toUpperCase()+' '+h.amount+' - '+(h.description||'')+'</li>'; });
                    html += '</ul>';
                    $('#wallet-histories').html(html);
                    $('#walletModal').modal('show');
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\PROJEK APLIKASI\delivery\resources\views/admin/wallets/index.blade.php ENDPATH**/ ?>