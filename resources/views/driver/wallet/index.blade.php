@extends('driver.layouts.app')

@section('driver-content')
    <h2>Wallet</h2>
    <div class="card">Balance: <strong>{{ $wallet ? $wallet->balance : 0 }}</strong></div>
@endsection