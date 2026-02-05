@extends('admin.layouts.app')
@section('title','Orders')
@section('content')
    <h1 class="page-title">Orders</h1>
    <table class="table">
        <thead><tr><th>#</th><th>Order No</th><th>Customer</th><th>Mitra</th><th>Driver</th><th>Status</th></tr></thead>
        <tbody>
        @foreach($orders as $o)
            <tr>
                <td>{{ $o->id }}</td>
                <td>{{ $o->order_no }}</td>
                <td>{{ $o->customer->name ?? '—' }}</td>
                <td>{{ $o->mitra->name ?? '—' }}</td>
                <td>{{ $o->driver->user->name ?? '—' }}</td>
                <td>{{ $o->status }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $orders->links() }}
@endsection