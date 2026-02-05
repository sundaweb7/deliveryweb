@extends('customer.layouts.app')

@section('customer-content')
    <h2>My Orders</h2>
    <table id="customer-orders-table" class="display" style="width:100%"><thead><tr><th>ID</th><th>Order No</th><th>Mitra</th><th>Status</th><th>Total</th><th>Aksi</th></tr></thead></table>

    <script>
    document.addEventListener('DOMContentLoaded', function(){
        const table = initDataTable('#customer-orders-table', '{{ route('customer.orders.data') }}', [
            { data:0 },{ data:1 },{ data:2 },{ data:3 },{ data:4 },{ data:5, orderable:false, searchable:false }
        ]);

        $('#customer-orders-table').on('click', '.btn-view', function(){
            let id = $(this).data('id');
            $.get('{{ url('customer/orders') }}/'+id, function(res){
                let items = res.items.map(i=> i.product.name + ' x '+ i.qty).join('\n');
                alert('Order '+res.order_no+'\nStatus: '+res.status+'\nItems:\n'+items);
            });
        });
    });
    </script>
@endsection