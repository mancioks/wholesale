@extends('layouts.admin')

@section('title')
    {{ __('Discount rules') }}
@endsection
@section('actions')
    @include('components.admin.modals.create-discount-rule')
@endsection
@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table" id="datatable" style="width:100%">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Applies to') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Range') }}</th>
                    <th>{{ __('Size') }}</th>
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
        let discountsDataTable;

        $(document).ready( function () {
            discountsDataTable = $('#datatable').DataTable({
                "ajax": "{{ route('api.datatable.discount-rules') }}",
                "columns": [
                    { "data": "id" },
                    { "data": "type" },
                    { "data": "model_name" },
                    { "data": "model.name" },
                    { "data": "range" },
                    { "data": "size" },
                    { "data": "", "orderable": false },
                ],
                columnDefs: [
                    {
                        targets: -1,
                        render: function (data, type, row) {
                            return '<a href="" class="btn btn-sm btn-primary">{{ __('Edit rule') }}</a>';
                        }
                    },
                    {
                        targets: 5,
                        render: function (data, type, row) {
                            if (data['type'] === 'percentage') {
                                return data + '%';
                            } else {
                                return data + '€';
                            }
                        }
                    },
                ]
            });
        } );

        document.addEventListener('livewire:load', function () {
            window.livewire.on('discountRuleCreated', () => {
                discountsDataTable.ajax.reload();
            });
        });
    </script>
@endsection
