@extends('admin.layouts.app')
@section('title','Login Histories')
@section('content')
    <h1 class="page-title">Login Histories</h1>

    <table id="login-histories-table" class="display" style="width:100%">
        <thead><tr><th>ID</th><th>User</th><th>Role</th><th>IP</th><th>User Agent</th><th>Created</th></tr></thead>
    </table>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const table = initDataTable('#login-histories-table','{{ route('admin.login_histories.index') }}' + '/data', [
                { data: 0 },{ data: 1 },{ data: 2 },{ data: 3 },{ data: 4 },{ data: 5 }
            ]);
        });
    </script>
@endsection
