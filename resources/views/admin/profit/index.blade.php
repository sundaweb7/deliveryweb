@extends('admin.layouts.app')
@section('title','Admin Profit')
@section('content')
    <h1 class="page-title">Admin Profit</h1>

    <div class="mb-3">
        <select id="profit-range" class="form-control" style="width:200px;display:inline-block;margin-right:8px"><option value="daily">Daily</option><option value="monthly">Monthly</option><option value="all" selected>All</option></select>
        <button id="btn-refresh-profit" class="btn">Refresh</button>
    </div>

    <div id="profit-result">Loading...</div>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            function load(range){
                $.get('{{ url('api/admin/profit') }}',{range:range},function(res){
                    $('#profit-result').html('<h3>Profit ('+res.range+')</h3><p>'+parseFloat(res.profit).toFixed(2)+'</p>');
                });
            }
            $('#btn-refresh-profit').on('click', function(){ load($('#profit-range').val()); });
            load('all');
        });
    </script>
@endsection