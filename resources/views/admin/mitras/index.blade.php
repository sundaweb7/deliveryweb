@extends('admin.layouts.app')
@section('title','Mitras')
@section('content')
    <h1 class="page-title">Mitras</h1>

    <div class="mb-3">
        <button id="btn-add-mitra" class="btn">Tambah Mitra</button>
    </div>

    <table id="mitra-table" class="display" style="width:100%">
        <thead><tr><th>ID</th><th>Nama</th><th>Address</th><th>Lat</th><th>Lng</th><th>Active</th><th>Aksi</th></tr></thead>
    </table>

    <!-- Modal -->
    <div class="modal" id="mitraModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="ajax-form" id="mitra-form" data-refresh-table="#mitra-table">
                    <div class="modal-header"><h5 class="modal-title">Mitra</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="mitra-id">
                        <div class="form-group"><label>User</label><select name="user_id" id="mitra-user_id" class="form-control">@foreach(\App\Models\User::where('role','mitra')->get() as $u)<option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>@endforeach</select></div>
                        <div class="form-group"><label>Name</label><input name="name" id="mitra-name" class="form-control"></div>
                        <div class="form-group"><label>Address</label><input name="address" id="mitra-address" class="form-control"></div>
                        <div class="form-group"><label>Business Name</label><input name="business_name" id="mitra-business_name" class="form-control"></div>
                        <div class="form-group"><label>Bank Account Number</label><input name="bank_account_number" id="mitra-bank_account_number" class="form-control" placeholder="628... atau nomor rekening"></div>
                        <div class="form-group"><label>Bank Name</label><input name="bank_name" id="mitra-bank_name" class="form-control"></div>
                        <div class="form-group"><label>Bank Account Name</label><input name="bank_account_name" id="mitra-bank_account_name" class="form-control"></div>
                        <div class="form-group"><label>Lat</label><input name="lat" id="mitra-lat" class="form-control"></div>
                        <div class="form-group"><label>Lng</label><input name="lng" id="mitra-lng" class="form-control"></div>
                        <div class="form-group"><label>Active</label><select name="is_active" id="mitra-active" class="form-control"><option value="1">Yes</option><option value="0">No</option></select></div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn">Simpan</button><button type="button" class="btn btn-outline" data-dismiss="modal">Batal</button></div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const table = initDataTable('#mitra-table', '{{ route('admin.mitras.index') }}' + '/data', [
                { data: 0 },
                { data: 1 },
                { data: 2 },
                { data: 3 },
                { data: 4 },
                { data: 5 },
                { data: 6, orderable: false, searchable: false }
            ]);

            $('#btn-add-mitra').on('click', function(){
                $('#mitra-form').attr('data-url','{{ route('admin.mitras.index') }}').attr('data-method','POST');
                $('#mitra-form')[0].reset();
                $('#mitra-id').val('');
                $('#mitraModal').modal('show');
            });

            $('#mitra-table').on('click','.btn-delete', function(){
                if (!confirm('Hapus mitra?')) return;
                let id = $(this).data('id');
                $.ajax({url: '{{ url('admin/mitras') }}/'+id, method: 'DELETE', headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, success:function(){table.ajax.reload()}});
            });

            $('#mitra-table').on('click','.btn-edit', function(){
                let id = $(this).data('id');
                $.get('{{ url('admin/mitras') }}/'+id, function(res){
                    $('#mitra-form').attr('data-url','{{ url('admin/mitras') }}/'+id).attr('data-method','PUT');
                    $('#mitra-id').val(res.id);
                    $('#mitra-name').val(res.name);
                    $('#mitra-business_name').val(res.business_name);
                    $('#mitra-address').val(res.address);
                    $('#mitra-bank_account_number').val(res.bank_account_number);
                    $('#mitra-bank_name').val(res.bank_name);
                    $('#mitra-bank_account_name').val(res.bank_account_name);
                    $('#mitra-lat').val(res.lat);
                    $('#mitra-lng').val(res.lng);
                    $('#mitra-active').val(res.is_active ? 1 : 0);
                    $('#mitra-user_id').val(res.user_id);
                    $('#mitraModal').modal('show');
                });
            });
        });
    </script>
@endsection