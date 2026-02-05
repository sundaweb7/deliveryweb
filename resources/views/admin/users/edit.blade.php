@extends('admin.layouts.app')
@section('title','Edit User')
@section('content')
    <h1 class="page-title">Edit User</h1>

    <form method="POST" action="">
        @csrf
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}">
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
        </div>
        <button class="btn btn-primary" type="submit">Simpan</button>
    </form>
@endsection