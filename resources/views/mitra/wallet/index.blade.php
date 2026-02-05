@extends('mitra.layouts.app')
@section('title','Wallet')
@section('content')
    <h1 class="page-title">Wallet</h1>
    <div class="card card-white">
        <div class="card-body">
            <h3>Rp {{ number_format($wallet->balance ?? 0,0,',','.') }}</h3>
            <div>Balance</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h4>Recent Histories</h4>
            <ul>
                @foreach($histories as $h)
                    <li>{{ $h->type }}: Rp {{ number_format($h->amount,0,',','.') }} ({{ $h->created_at }})</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection