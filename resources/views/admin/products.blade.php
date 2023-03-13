@extends('layouts.admin')

@section('title')
    {{ __('Products') }}
@endsection
@section('actions')
    @include('components.admin.modals.create-product')
    @include('components.admin.dashboard-action', ['route' => route('admin.product-import'), 'title' => __('Import products'), 'class' => 'btn-warning', 'icon' => 'bi bi-filetype-csv'])
    @include('components.admin.dashboard-action', ['route' => route('admin.products.export'), 'title' => __('Export products'), 'class' => 'btn-success', 'icon' => 'bi bi-file-earmark-spreadsheet'])
    <button class="btn btn-danger btn-sm d-none" id="bulk-delete-button" onclick="deleteSelectedProducts()"><i class="bi bi-trash3-fill"></i> {{ __('Delete selected') }}</button>
@endsection
@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table" id="datatable" style="width:100%">
                <thead>
                <tr>
                    <th></th>
                    <th>Id</th>
                    <th></th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Code') }}</th>
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
                    { "data": "", "orderable": false },
                    { "data": "id" },
                    { "data": "", "orderable": false },
                    { "data": "name" },
                    { "data": "code" },
                    { "data": "price" },
                    { "data": "", "orderable": false },
                ],
                columnDefs: [
                    {
                        targets: 0,
                        render: function (data, type, row) {
                            return '<input type="checkbox" class="form-check-input" name="products[]" value="' + row.id + '">';
                        }
                    },
                    {
                        targets: -1,
                        render: function (data, type, row) {
                            return '<a href="' + ('{{ route('admin.product.show', '%id%') }}').replace('%id%', row['id']) + '" class="btn btn-sm btn-primary">{{ __('Show product') }}</a> ' +
                                   '<form action="' + ('{{ route('product.destroy', '%id%') }}').replace('%id%', row['id']) + '" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure?\')">' +
                                   '@csrf' +
                                   '@method('DELETE')' +
                                   '<button type="submit" class="btn btn-sm btn-danger">{{ __('Delete') }}</button>' +
                                   '</form>';
                        }
                    },
                    {
                        targets: 2,
                        render: function (data, type, row) {
                            return '<img src="' + ('{{ asset('%image%') }}').replace('%image%', row['image']) + '" class="card-img-top w-auto" style="height: 30px;">';
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

        function deleteSelectedProducts() {
            let products = [];
            $('input[name="products[]"]:checked').each(function() {
                products.push($(this).val());
            });

            if (products.length === 0) {
                alert('{{ __('Please select at least one product') }}');
                return;
            }

            if (!confirm('{{ __('Are you sure?') }}')) {
                return;
            }

            $.ajax({
                url: '{{ route('product.bulk-destroy') }}',
                type: 'DELETE',
                data: {
                    products: products,
                    _token: '{{ csrf_token() }}'
                },
                success: function (data) {
                    productsDataTable.ajax.reload();
                    $('#bulk-delete-button').addClass('d-none');

                    // wait 100 ms
                    setTimeout(function () {
                        alert('{{ __('Products deleted successfully') }}');
                    }, 100);
                }
            });
        }

        $(document).ready( function () {
            $('#datatable').on('change', 'input[name="products[]"]', function() {
                if ($('input[name="products[]"]:checked').length > 0) {
                    $('#bulk-delete-button').removeClass('d-none');
                } else {
                    $('#bulk-delete-button').addClass('d-none');
                }
            });
        } );
    </script>
@endsection
