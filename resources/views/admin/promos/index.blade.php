@extends('admin.layouts.app')
@section('title','Promos')
@section('content')
    <h1 class="page-title">Promos</h1>

    <div class="mb-3">
        <button id="btn-add-promo" class="btn">Tambah Promo</button>
    </div>

    <table id="promos-table" class="display" style="width:100%">
        <thead><tr><th>ID</th><th>Code</th><th>Title</th><th>Type</th><th>Value</th><th>Limit</th><th>Used</th><th>Status</th><th>Aksi</th></tr></thead>
    </table>

    <!-- Modal -->
    <div class="modal" id="promoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="ajax-form" id="promo-form" data-refresh-table="#promos-table">
                    <div class="modal-header"><h5 class="modal-title">Promo</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="promo-id">
                        <div class="form-group"><label>Code</label><input name="code" id="promo-code" class="form-control"></div>
                        <div class="form-group"><label>Title</label><input name="title" id="promo-title" class="form-control"></div>
                        <div class="form-group"><label>Type</label><select name="type" id="promo-type" class="form-control"><option value="percent">Percent</option><option value="fixed">Fixed</option></select></div>
                        <div class="form-group"><label>Value</label><input name="value" id="promo-value" class="form-control"></div>
                        <div class="form-group"><label>Min Order</label><input name="min_order" id="promo-min_order" class="form-control"></div>
                        <div class="form-group"><label>Max Discount</label><input name="max_discount" id="promo-max_discount" class="form-control"></div>
                        <div class="form-group"><label>Start Date</label><input name="start_date" id="promo-start_date" class="form-control" type="datetime-local"></div>
                        <div class="form-group"><label>End Date</label><input name="end_date" id="promo-end_date" class="form-control" type="datetime-local"></div>
                        <div class="form-group"><label>Usage Limit</label><input name="usage_limit" id="promo-usage_limit" class="form-control" type="number"></div>
                        <div class="form-group"><label>Status</label><select name="status" id="promo-status" class="form-control"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn">Simpan</button><button type="button" class="btn btn-outline" data-dismiss="modal">Batal</button></div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const table = initDataTable('#promos-table', '{{ route('admin.promos.index') }}' + '/data', [
                { data: 0 },{ data: 1 },{ data: 2 },{ data: 3 },{ data: 4 },{ data: 5 },{ data: 6 },{ data: 7 },{ data: 8, orderable:false, searchable:false }
            ]);

            $('#btn-add-promo').on('click', function(){
                $('#promo-form').attr('data-url','{{ url('admin/promos') }}').attr('data-method','POST');
                $('#promo-form')[0].reset();
                $('#promo-id').val('');
                $('#promoModal').modal('show');
            });

            $('#promos-table').on('click','.btn-delete', function(){
                if (!confirm('Hapus promo?')) return;
                let id = $(this).data('id');
                $.ajax({url: '{{ url('admin/promos') }}/'+id, method: 'DELETE', headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, success:function(){table.ajax.reload()}});
            });

            $('#promos-table').on('click','.btn-edit', function(){
                let id = $(this).data('id');
                $.get('{{ url('admin/promos') }}/'+id, function(res){
                    $('#promo-form').attr('data-url','{{ url('admin/promos') }}/'+id).attr('data-method','PUT');
                    $('#promo-id').val(res.id);
                    $('#promo-code').val(res.code);
                    $('#promo-title').val(res.title);
                    $('#promo-type').val(res.type);
                    $('#promo-value').val(res.value);
                    $('#promo-min_order').val(res.min_order);
                    $('#promo-max_discount').val(res.max_discount);
                    $('#promo-usage_limit').val(res.usage_limit);
                    $('#promo-status').val(res.status);
                    if (res.start_date) $('#promo-start_date').val(new Date(res.start_date).toISOString().slice(0,16));
                    if (res.end_date) $('#promo-end_date').val(new Date(res.end_date).toISOString().slice(0,16));
                    $('#promoModal').modal('show');
                });
            });
        });
    </script>
@endsection