@extends('layouts.admin')

@section('title')
    {{ __('Users') }}
@endsection
@section('actions')
    @include('components.admin.dashboard-action', ['route' => route('user.create'), 'title' => __('Create user'), 'class' => 'btn-primary', 'icon' => 'bi bi-plus-circle'])
@endsection
@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table" id="datatable">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Role') }}</th>
                    <th>{{ __('Activated') }}</th>
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
                "ajax": "{{ route('api.datatable.users') }}",
                "columns": [
                    { "data": "id" },
                    { "data": "name" },
                    { "data": "email" },
                    { "data": "role_id" },
                    { "data": "activated" },
                    { "data": "", "orderable": false },
                ],
                columnDefs: [
                    {
                        targets: -1,
                        render: function (data, type, row) {
                            return '<a href="' + ('{{ route('user.show', '%id%') }}').replace('%id%', row['id']) + '" class="btn btn-sm btn-primary">{{ __('View') }}</a> ' +
                                   '<a href="' + ('{{ route('user.act-as', '%id%') }}').replace('%id%', row['id']) + '" class="btn btn-sm btn-secondary">{{ __('Create order') }}</a> ' +
                                   '<a href="' + ('{{ route('user.edit', '%id%') }}').replace('%id%', row['id']) + '" class="btn btn-sm btn-warning">{{ __('Edit') }}</a> ' +
                                   '<a href="' + ('{{ route('user.destroy', '%id%') }}').replace('%id%', row['id']) + '" class="btn btn-sm btn-danger" onclick="confirm(\'Are you sure?\')">{{ __('Delete') }}</a> ';
                        }
                    },
                ]
            });
        } );
    </script>
@endsection
