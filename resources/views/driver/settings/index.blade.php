@extends('driver.layouts.app')

@section('driver-content')
    <h2>Settings</h2>
    <form class="ajax-form" id="driver-settings" data-url="{{ route('driver.settings.update') }}" data-method="POST">
        <div class="form-group"><label>Name</label><input name="name" class="form-control" value="{{ $user->name ?? '' }}"></div>
        <div class="form-group"><label>Phone</label><input name="phone" class="form-control" value="{{ $user->phone ?? '' }}"></div>
        <button class="btn">Simpan</button>
    </form>
@endsection