@extends('admin.layouts.app')
@section('title','Order Timeline')
@section('content')
    <h1 class="page-title">Order Timeline - {{ $order->order_no }}</h1>

    <div class="timeline">
        @foreach($order->timelines as $t)
            <div class="timeline-item">
                <div class="time">{{ $t->created_at }}</div>
                <div class="status">{{ $t->status }}</div>
                <div class="note">{{ $t->note }}</div>
            </div>
        @endforeach
    </div>

    <style>
        .timeline { border-left:3px solid #eee; padding-left:10px }
        .timeline-item { margin-bottom:15px }
        .timeline-item .time { font-size:12px;color:#999 }
        .timeline-item .status { font-weight:bold }
    </style>
@endsection