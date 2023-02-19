@extends('layouts.admin')

@section('title')
    {{ __('Inventorization') }}
@endsection
@section('actions')
    @include('components.admin.modals.create-inventorization', ['warehouses' => $warehouses])
@endsection
@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table" id="datatable" style="width:100%">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>{{ __('Warehouse') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('User') }}</th>
                    <th>{{ __('Created') }}</th>
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
        let ordersDataTable;

        $(document).ready( function () {
            ordersDataTable = $('#datatable').DataTable({
                "ajax": "{{ route('api.datatable.inventorizations') }}",
                "columns": [
                    { "data": "id" },
                    { "data": "warehouse.name" },
                    { "data": "date" },
                    { "data": "user.name" },
                    { "data": "created_at" },
                    { "data": "", "orderable": false },
                ],
                columnDefs: [
                    {
                        targets: -1,
                        render: function (data, type, row) {
                            return '<a href="' + ('{{ route('admin.inventorization.show', '%id%') }}').replace('%id%', row['id']) + '" class="btn btn-sm btn-primary">{{ __('Show') }}</a> ' +
                                '<form action="' + ('{{ route('admin.inventorization.destroy', '%id%') }}').replace('%id%', row['id']) + '" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure?\')">' +
                                '@csrf' +
                                '@method('DELETE')' +
                                '<button type="submit" class="btn btn-sm btn-danger">{{ __('Delete') }}</button>' +
                                '</form>';
                        }
                    },
                ]
            });
        } );
    </script>
@endsection
