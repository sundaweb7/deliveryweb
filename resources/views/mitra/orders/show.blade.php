@extends('mitra.layouts.app')
@section('title','Order #'.$order->id)
@section('content')
    <h1 class="page-title">Order #{{ $order->id }}</h1>
    <div class="card card-white">
        <div class="card-body">
            <p><strong>Customer:</strong> {{ $order->user->name ?? '-' }}</p>
            <p><strong>Status:</strong> {{ $order->status }}</p>
            <p><strong>Total:</strong> Rp {{ number_format($order->total_price,0,',','.') }}</p>
            <p><strong>Items:</strong></p>
            <ul>
                @foreach($order->items as $it)
                    <li>{{ $it->product_name }} x {{ $it->quantity }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection