@extends('customer.layouts.app')

@section('customer-content')
    <h2>Profile Settings</h2>
    <form class="ajax-form" id="customer-settings" data-url="{{ route('customer.settings.update') }}" data-method="POST">
        <div class="form-group"><label>Name</label><input name="name" class="form-control" value="{{ $user->name ?? '' }}"></div>
        <div class="form-group"><label>Phone</label><input name="phone" class="form-control" value="{{ $user->phone ?? '' }}"></div>
        <div class="form-group"><label>Address</label><textarea name="address" class="form-control">{{ $user->address ?? '' }}</textarea></div>
        <button class="btn">Simpan</button>
    </form>
@endsection