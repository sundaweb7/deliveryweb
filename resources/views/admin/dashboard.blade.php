@extends('admin.layouts.app')
@section('title','Dashboard')
@section('content')
    <h1 class="page-title">Dashboard</h1>

    <div class="grid">
        <div class="card card-pink">
            <div class="card-body">
                <h3>{{ $todayOrders }}</h3>
                <p>Orders Hari Ini</p>
            </div>
        </div>

        <div class="card card-blue">
            <div class="card-body">
                <h3>{{ number_format($totalIncome) }}</h3>
                <p>Total Income</p>
            </div>
        </div>

        <div class="card card-white">
            <div class="card-body">
                <h3>{{ number_format($adminProfit) }}</h3>
                <p>Admin Profit</p>
            </div>
        </div>

        <div class="card card-lightpink">
            <div class="card-body">
                <h3>{{ $driversActive }}</h3>
                <p>Driver Aktif</p>
            </div>
        </div>

        <div class="card card-green">
            <div class="card-body">
                <h3>{{ $totalMitra }}</h3>
                <p>Total Mitra</p>
            </div>
        </div>

        <div class="card card-orange">
            <div class="card-body">
                <h3>{{ $totalUsers }}</h3>
                <p>Total User</p>
            </div>
        </div>

        <div class="card card-purple">
            <div class="card-body">
                <h3>{{ $totalProducts }}</h3>
                <p>Total Produk</p>
            </div>
        </div>
    </div>

@endsection