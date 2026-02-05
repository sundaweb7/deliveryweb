@extends('customer.layouts.app')

@section('customer-content')
    <h2>Dashboard</h2>
    <div class="row">
        <div class="col"><div class="card">Orders: <strong>{{ $ordersCount }}</strong></div></div>
        <div class="col"><div class="card">Pending: <strong>{{ $pending }}</strong></div></div>
        <div class="col"><div class="card">Delivered: <strong>{{ $delivered }}</strong></div></div>
        <div class="col"><div class="card">Wallet: <strong>{{ $wallet ? $wallet->balance : 0 }}</strong></div></div>
    </div>
@endsection