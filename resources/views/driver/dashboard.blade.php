@extends('driver.layouts.app')

@section('driver-content')
    <h2>Dashboard</h2>
    <div class="row">
        <div class="col"><div class="card">Assigned: <strong>{{ $assigned }}</strong></div></div>
        <div class="col"><div class="card">On Delivery: <strong>{{ $onDelivery }}</strong></div></div>
        <div class="col"><div class="card">Delivered: <strong>{{ $delivered }}</strong></div></div>
        <div class="col"><div class="card">Wallet: <strong>{{ $wallet ? $wallet->balance : 0 }}</strong></div></div>
    </div>
@endsection