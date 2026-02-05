@extends('mitra.layouts.app')
@section('title','Orders')
@section('content')
    <h1 class="page-title">Orders</h1>
    <table id="order-table" class="display" style="width:100%"><thead><tr><th>ID</th><th>Customer</th><th>Status</th><th>Total</th><th>Created</th><th>Aksi</th></tr></thead></table>

<script>
document.addEventListener('DOMContentLoaded', function(){
    initDataTable('#order-table','{{ route('mitra.orders.data') }}',[{data:0},{data:1},{data:2},{data:3},{data:4},{data:5,orderable:false,searchable:false}]);
});
</script>
@endsection