@extends('admin.layouts.app')
@section('title','Driver Locations')
@section('content')
    <h1 class="page-title">Driver Locations</h1>

    <table id="driver-locations-table" class="display" style="width:100%">
        <thead><tr><th>ID</th><th>Driver</th><th>Lat</th><th>Lng</th><th>Updated</th></tr></thead>
    </table>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const table = initDataTable('#driver-locations-table', '{{ route('admin.driver_locations.index') }}' + '/data', [
                { data: 'id' },
                { data: function(row){ return row.driver ? (row.driver.name || 'Driver#'+row.driver.id) : 'Driver#'+row.driver_id } },
                { data: 'lat' },
                { data: 'lng' },
                { data: 'updated_at' }
            ]);
        });
    </script>
@endsection