@extends('admin.layouts.app')
@section('title','WA Broadcasts')
@section('content')
    <h1 class="page-title">WA Broadcasts</h1>

    <div class="mb-3">
        <button id="btn-add-bc" class="btn">Tambah Broadcast</button>
    </div>

    <table id="bcs-table" class="display" style="width:100%">
        <thead><tr><th>ID</th><th>Title</th><th>Target</th><th>Status</th><th>Created</th><th>Aksi</th></tr></thead>
    </table>

    <!-- Modal -->
    <div class="modal" id="bcModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="ajax-form" id="bc-form" data-refresh-table="#bcs-table">
                    <div class="modal-header"><h5 class="modal-title">Broadcast</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                    <div class="modal-body">
                        <input type="hidden" id="bc-id">
                        <div class="form-group"><label>Title</label><input name="title" id="bc-title" class="form-control"></div>
                        <div class="form-group"><label>Message</label><textarea name="message" id="bc-message" class="form-control"></textarea></div>
                        <div class="form-group"><label>Target</label><select name="target" id="bc-target" class="form-control"><option value="all">All</option><option value="customer">Customer</option><option value="mitra">Mitra</option><option value="driver">Driver</option></select></div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn">Kirim</button><button type="button" class="btn btn-outline" data-dismiss="modal">Batal</button></div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const table = initDataTable('#bcs-table', '{{ route('admin.wa_broadcasts.index') }}' + '/data', [
                { data: 0 },{ data: 1 },{ data: 2 },{ data: 3 },{ data: 4 },{ data: 5, orderable:false, searchable:false }
            ]);

            $('#btn-add-bc').on('click', function(){
                $('#bc-form').attr('data-url','{{ url('admin/wa-broadcasts') }}').attr('data-method','POST');
                $('#bc-form')[0].reset();
                $('#bcModal').modal('show');
            });
        });
    </script>
@endsection