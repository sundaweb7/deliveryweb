@extends('admin.layouts.app')
@section('title','Settings')
@section('content')
    <h1 class="page-title">Settings</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        <div class="form-group">
            <label>Price per km (IDR)</label>
            <input type="number" name="price_per_km" class="form-control" value="{{ $settings['price_per_km'] ?? '' }}">
            @error('price_per_km') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Admin Fee Percent (%)</label>
            <input type="number" name="admin_fee_percent" class="form-control" value="{{ $settings['admin_fee_percent'] ?? '' }}">
            @error('admin_fee_percent') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Max Radius (km)</label>
            <input type="number" step="0.1" name="max_radius_km" class="form-control" value="{{ $settings['max_radius_km'] ?? '' }}">
            @error('max_radius_km') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <hr>
        <h4>WA Provider (Mpedia) ⚙️</h4>
        <div class="form-group">
            <label>MPEDIA API KEY</label>
            <input type="text" name="mpedia_api_key" class="form-control" value="{{ $settings['mpedia_api_key'] ?? env('MPEDIA_API_KEY') }}">
            @error('mpedia_api_key') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label>MPEDIA SENDER</label>
            <input type="text" name="mpedia_sender" class="form-control" value="{{ $settings['mpedia_sender'] ?? env('MPEDIA_SENDER') }}">
            @error('mpedia_sender') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <hr>
        <h4>Duitku Payment Gateway ⚙️</h4>
        <div class="form-group">
            <label>Merchant Code</label>
            <input type="text" name="duitku_merchant_code" class="form-control" value="{{ $settings['duitku_merchant_code'] ?? env('DUITKU_MERCHANT_CODE') }}">
            @error('duitku_merchant_code') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label>Merchant Key</label>
            <input type="text" name="duitku_merchant_key" class="form-control" value="{{ $settings['duitku_merchant_key'] ?? env('DUITKU_MERCHANT_KEY') }}">
            @error('duitku_merchant_key') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label>API URL</label>
            <input type="text" name="duitku_api_url" class="form-control" value="{{ $settings['duitku_api_url'] ?? env('DUITKU_API_URL') }}">
            @error('duitku_api_url') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label>Test Mode</label>
            <div class="form-check">
                <input type="checkbox" name="duitku_is_test_mode" value="1" class="form-check-input" id="duitku-test-mode" {{ (isset($settings['duitku_is_test_mode']) ? $settings['duitku_is_test_mode'] == '1' : (env('DUITKU_IS_TEST_MODE') ? true : false)) ? 'checked' : '' }}>
                <label class="form-check-label" for="duitku-test-mode">Enable test mode</label>
            </div>
            @error('duitku_is_test_mode') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button class="btn btn-primary">Save Settings</button>
    </form>
@endsection