@extends('mitra.layouts.app')

@section('title','Mitra Dashboard')

@section('content')
    <div class="page-title">Mitra Dashboard</div>
    <div class="grid">
        <div class="card card-pink">
            <div class="card-body">
                <h3>{{ $productCount }}</h3>
                <div>Products</div>
            </div>
        </div>
        <div class="card card-blue">
            <div class="card-body">
                <h3>{{ $orderCount }}</h3>
                <div>Orders</div>
            </div>
        </div>
        <div class="card card-white">
            <div class="card-body">
                <h3>Rp {{ number_format($wallet,0,',','.') }}</h3>
                <div>Wallet Balance</div>
            </div>
        </div>
    </div>
@endsection