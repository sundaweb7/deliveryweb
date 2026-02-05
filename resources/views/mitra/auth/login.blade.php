<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mitra Login</title>
    <link rel="stylesheet" href="/vendor/theme/Gxon_v1_22-Dec-2025/demo/public/assets/css/styles.css">
    <link rel="stylesheet" href="/css/admin-custom.css">
</head>
<body>
<div class="login-container">
    <div class="login-box login-box-mitra">
        <div class="logo text-center"><img src="/images/logoAd.png" alt="Logo" style="height:80px;margin-bottom:12px"></div>
        @include('auth.login-tabs')
        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
        <h2 class="page-title">Mitra Login</h2>
        <form method="POST" action="{{ route('mitra.login.post') }}">
            @csrf
            <div class="form-group"><label>Email</label><input type="email" name="email" value="{{ old('email') }}" required class="form-control"></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" required class="form-control"></div>
            <div class="form-group" style="margin-top:12px"><button class="btn btn-primary btn-block" type="submit">Login</button></div>
        </form>
    </div>
</div>
</body>
</html>