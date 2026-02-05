@extends('driver.layouts.app')

@section('title','Driver Login')

@section('driver-content')
    <div style="max-width:420px;margin:40px auto">
        <div class="card">
            <div class="card-body">
                <div class="logo text-center"><img src="/images/logoAd.png" alt="Logo" style="height:80px;margin-bottom:12px"></div>
                @include('auth.login-tabs')
                @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
                <h3>Driver Login</h3>
                <form method="POST" action="{{ route('driver.login.post') }}">
                    @csrf
                    <div class="form-group"><label>Email</label><input name="email" type="email" class="form-control" required></div>
                    <div class="form-group"><label>Password</label><input name="password" type="password" class="form-control" required></div>
                    <button class="btn">Login</button>
                </form>
            </div>
        </div>
    </div>
@endsection