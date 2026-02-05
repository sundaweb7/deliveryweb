<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Customer Area')</title>
    <link rel="icon" href="/images/iconAD.png">
    <link rel="stylesheet" href="/vendor/theme/Gxon_v1_22-Dec-2025/demo/public/assets/css/styles.css">
    <link rel="stylesheet" href="/css/admin-custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<header class="admin-topbar">
    <div class="brand">
        <img src="/images/logoAd.png" alt="Logo" style="height:40px">
        <button class="btn btn-icon sidebar-toggle" aria-label="Toggle sidebar"><i class="fa fa-bars"></i></button>
    </div>
    @unless(request()->routeIs('customer.login'))
    <nav class="top-actions">
        <form action="{{ route('customer.logout') }}" method="POST">
            @csrf
            <button class="btn btn-outline" type="submit">Logout</button>
        </form>
    </nav>
    @endunless
</header>
<div class="admin-wrapper">
    @unless(request()->routeIs('customer.login'))
    <aside class="admin-sidebar">
        <ul class="nav">
            <li><a href="{{ route('customer.dashboard') }}" class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}"><i class="fa fa-tachometer-alt"></i><span class="nav-label">Dashboard</span></a></li>
            <li><a href="{{ route('customer.orders.index') }}" class="nav-link {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}"><i class="fa fa-shopping-basket"></i><span class="nav-label">My Orders</span></a></li>
            <li><a href="{{ route('customer.wallet.index') }}" class="nav-link {{ request()->routeIs('customer.wallet.*') ? 'active' : '' }}"><i class="fa fa-wallet"></i><span class="nav-label">Wallet</span></a></li>
            <li><a href="{{ route('customer.settings.index') }}" class="nav-link {{ request()->routeIs('customer.settings.*') ? 'active' : '' }}"><i class="fa fa-user"></i><span class="nav-label">Profile</span></a></li>
        </ul>
    </aside>
    @endunless

    <main class="admin-content">
        <div class="container">
            @yield('customer-content')
        </div>
    </main>
</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="/js/admin-crud.js"></script>
    <script src="/vendor/theme/Gxon_v1_22-Dec-2025/demo/public/assets/js/main.js"></script>
</body>
</html>