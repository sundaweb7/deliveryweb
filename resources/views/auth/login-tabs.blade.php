<div class="login-tabs">
    <a href="{{ route('admin.login') }}" class="tab tab-admin {{ request()->routeIs('admin.login') ? 'active' : '' }}" aria-label="Login Admin">
        <span>Login Admin</span>
    </a>
    <a href="{{ route('driver.login') }}" class="tab tab-driver {{ request()->routeIs('driver.login') ? 'active' : '' }}" aria-label="Login Driver">
        <span>Login Driver</span>
    </a>
    <a href="{{ route('mitra.login') }}" class="tab tab-mitra {{ request()->routeIs('mitra.login') ? 'active' : '' }}" aria-label="Login Mitra">
        <span>Login Mitra</span>
    </a>
    <a href="{{ route('customer.login') }}" class="tab tab-customer {{ request()->routeIs('customer.login') ? 'active' : '' }}" aria-label="Login Customer">
        <span>Login Customer</span>
    </a>
</div>