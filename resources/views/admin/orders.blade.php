@extends('layouts.admin')

@section('title')
    {{ __('Orders') }}
@endsection
@section('actions')
@endsection
@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table" id="datatable">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>{{ __('User') }}</th>
                    <th>{{ __('Total') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Created') }}</th>
                    <th>{{ __('Updated') }}</th>
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
                "ajax": "{{ route('api.datatable.orders') }}",
                "columns": [
                    { "data": "number" },
                    { "data": "user.name" },
                    { "data": "total" },
                    { "data": "status.name" },
                    { "data": "created_at" },
                    { "data": "updated_at" },
                    { "data": "", "orderable": false },
                ],
                columnDefs: [
                    {
                        targets: -1,
                        render: function (data, type, row) {
                            return '<a href="' + ('{{ route('order.show', '%id%') }}').replace('%id%', row['id']) + '" class="btn btn-sm btn-primary">{{ __('Show order') }}</a>';
                        }
                    },
                    {
                        targets: 3,
                        render: function (data, type, row) {
                            return '<span class="badge ' + row['statuscolor'] + '">' + data + '</span>';
                        }
                    },
                    {
                        targets: 2,
                        render: function (data, type, row) {
                            return data + '€';
                        }
                    },
                ]
            });
        } );
    </script>
@endsection
