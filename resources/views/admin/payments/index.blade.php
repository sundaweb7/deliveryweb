@extends('admin.layouts.app')
@section('title','Payments')
@section('content')
    <h1 class="page-title">Payments</h1>

    <table id="payments-table" class="display" style="width:100%">
        <thead><tr><th>Order Code</th><th>Reference</th><th>Method</th><th>Amount</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
    </table>

    <!-- Detail Modal -->
    <div class="modal" id="paymentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Payment Detail</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                <div class="modal-body" id="payment-detail"></div>
                <div class="modal-footer"><button type="button" class="btn btn-outline" data-dismiss="modal">Close</button></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const table = initDataTable('#payments-table', '{{ route('admin.payments.index') }}' + '/data', [
                { data: 0 },
                { data: 1 },
                { data: 2 },
                { data: 3 },
                { data: 4 },
                { data: 5 },
                { data: 6, orderable:false, searchable:false }
            ]);

            $('#payments-table').on('click','.btn-show', function(){
                let id = $(this).data('id');
                $.get('{{ url('admin/payments') }}/'+id, function(res){
                    let html = '<p><b>Order:</b> '+(res.order.order_no ?? '-')+'</p>';
                    html += '<p><b>Reference:</b> '+res.reference+'</p>';
                    html += '<p><b>Method:</b> '+res.method+'</p>';
                    html += '<p><b>Amount:</b> '+res.amount+'</p>';
                    html += '<pre>'+JSON.stringify(res.raw_callback||res.meta||{},null,2)+'</pre>';
                    $('#payment-detail').html(html);
                    $('#paymentModal').modal('show');
                });
            });
        });
    </script>
@endsection