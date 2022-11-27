@extends('layouts.admin')

@section('title')
    {{ __('Products') }}
@endsection
@section('actions')
    @include('components.admin.modals.create-product')
    @include('components.admin.dashboard-action', ['route' => route('admin.product-import'), 'title' => __('Import products'), 'class' => 'btn-warning', 'icon' => 'bi bi-filetype-csv'])
    @include('components.admin.dashboard-action', ['route' => route('admin.products.export'), 'title' => __('Export products'), 'class' => 'btn-success', 'icon' => 'bi bi-file-earmark-spreadsheet'])
@endsection
@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table" id="datatable" style="width:100%">
                <thead>
                <tr>
                    <th>Id</th>
                    <th></th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Code') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Prime cost') }}</th>
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
        let productsDataTable;

        $(document).ready( function () {
            productsDataTable = $('#datatable').DataTable({
                "ajax": "{{ route('api.datatable.products') }}",
                "columns": [
                    { "data": "id" },
                    { "data": "", "orderable": false },
                    { "data": "name" },
                    { "data": "code" },
                    { "data": "price" },
                    { "data": "prime_cost" },
                    { "data": "", "orderable": false },
                ],
                columnDefs: [
                    {
                        targets: -1,
                        render: function (data, type, row) {
                            return '<a href="' + ('{{ route('product.edit', '%id%') }}').replace('%id%', row['id']) + '" class="btn btn-sm btn-primary">{{ __('Edit') }}</a> ' +
                                   '<a href="' + ('{{ route('product.destroy', '%id%') }}').replace('%id%', row['id']) + '" class="btn btn-sm btn-danger" onclick="confirm(\'Are you sure?\')">{{ __('Delete') }}</a>';
                        }
                    },
                    {
                        targets: 1,
                        render: function (data, type, row) {
                            return '<img src="' + ('{{ asset('%image%') }}').replace('%image%', row['image']) + '" class="card-img-top w-auto" style="height: 30px;">';
                        }
                    },
                    {
                        targets: 4,
                        render: function (data, type, row) {
                            return data + '€';
                        }
                    },
                    {
                        targets: 5,
                        render: function (data, type, row) {
                            return data + '€';
                        }
                    },
                ]
            });
        } );

        document.addEventListener('livewire:load', function () {
            window.livewire.on('productCreated', () => {
                productsDataTable.ajax.reload();
            });
        });
    </script>
@endsection
