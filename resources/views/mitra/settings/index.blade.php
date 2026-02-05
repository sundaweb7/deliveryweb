@extends('mitra.layouts.app')
@section('title','Settings')
@section('content')
    <h1 class="page-title">Settings</h1>
    <form method="POST" action="{{ route('mitra.settings.update') }}">
        @csrf
        <div class="form-group"><label>Name</label><input name="name" value="{{ $user->name }}" class="form-control"></div>
        <div class="form-group"><label>Phone</label><input name="phone" value="{{ $user->phone }}" class="form-control"></div>
        <div class="form-group"><label>New Password (leave blank to keep)</label><input name="password" class="form-control" type="password"></div>
        <div style="margin-top:12px"><button class="btn" type="submit">Save</button></div>
        @if(session('success'))<div style="color:green;margin-top:8px">{{ session('success') }}</div>@endif
    </form>
@endsection