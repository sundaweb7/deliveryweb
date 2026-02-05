@extends('admin.layouts.app')
@section('title','System Logs')
@section('content')
    <h1 class="page-title">System Logs</h1>

    <table id="logs-table" class="display" style="width:100%">
        <thead><tr><th>ID</th><th>User</th><th>Action</th><th>Description</th><th>IP</th><th>Created</th></tr></thead>
    </table>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const table = initDataTable('#logs-table','{{ route('admin.system_logs.index') }}' + '/data', [
                { data: 0 },{ data: 1 },{ data: 2 },{ data: 3 },{ data: 4 },{ data: 5 }
            ]);
        });
    </script>
@endsection