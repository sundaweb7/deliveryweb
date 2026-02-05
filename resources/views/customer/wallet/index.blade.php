@extends('customer.layouts.app')

@section('customer-content')
    <h2>Wallet</h2>
    <div class="card">Balance: <strong>{{ $wallet ? $wallet->balance : 0 }}</strong></div>
@endsection