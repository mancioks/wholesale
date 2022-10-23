@extends('layouts.admin')

@section('title')
    {{ __('Warehouses') }}
@endsection
@section('actions')
    @include('components.admin.dashboard-action', ['route' => route('warehouse.create'), 'title' => __('Add warehouse'), 'class' => 'btn-primary', 'icon' => 'bi bi-plus-circle'])
@endsection
@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table" id="datatable">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Active') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('scripts')
    <script>
        $(document).ready( function () {
            $('#datatable').DataTable({
                "ajax": "{{ route('api.datatable.warehouses') }}",
                "columns": [
                    { "data": "id" },
                    { "data": "name" },
                    { "data": "active" },
                    { "data": "", "orderable": false },
                ],
                columnDefs: [{
                    targets: 3,
                    render: function (data, type, row) {
                        return '<a href="' + ('{{ route('warehouse.edit', '%id%') }}').replace('%id%', row['id']) + '" class="btn btn-sm btn-primary">{{ __('Edit') }}</a>';
                    }
                }]
            });
        } );
    </script>
@endsection
