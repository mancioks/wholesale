@extends('layouts.admin')

@section('title')
    {{ __('BONUS calculator') }}
@endsection

@section('actions')
    @include('components.admin.dashboard-action', ['route' => route('admin.tools.bonus_calculator.create'), 'title' => __('Create'), 'class' => 'btn-success', 'icon' => 'bi bi-plus-circle'])
    @include('components.admin.dashboard-action', ['route' => route('admin.tools.bonus_calculator.templates'), 'title' => __('Templates'), 'class' => 'btn-warning', 'icon' => 'bi bi-layers'])
    @include('components.admin.modals.calculator-installers')
    @include('components.admin.modals.calculator-managers')
    @include('components.admin.modals.calculator-services')
@endsection

@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-4">
                    <label for="manager-filter" class="form-label">{{ __('Manager') }}</label>
                    <select class="form-select form-select-sm" id="manager-filter">
                        <option value="">{{ __('All') }}</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->name }}">{{ $manager->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4">
                    <label for="installer-filter" class="form-label">{{ __('Installer') }}</label>
                    <select class="form-select form-select-sm" id="installer-filter">
                        <option value="">{{ __('All') }}</option>
                        @foreach($installers as $installer)
                            <option value="{{ $installer->name }}">{{ $installer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4">
                    <label for="date-from" class="form-label">{{ __('Date') }}</label>
                    <div class="input-daterange input-group input-group-sm">
                        <label for="date-from" class="input-group-text">{{ __('From') }}</label>
                        <input type="text" id="date-from" class="form-control" name="start">
                        <label for="date-to" class="input-group-text">{{ __('To') }}</label>
                        <input type="text" id="date-to" class="form-control" name="end">
                    </div>
                </div>
            </div>

            <table class="table" id="datatable" style="width:100%">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Manager') }}</th>
                    <th>{{ __('Installer') }}</th>
                    <th>{{ __('Object') }}</th>
                    <th>{{ __('Calculated by') }}</th>
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
        let calculationsDataTable;

        $(document).ready( function () {
            calculationsDataTable = $('#datatable').DataTable({
                "ajax": "{{ route('api.datatable.calculations') }}",
                "columns": [
                    { "data": "id" },
                    { "data": "date" },
                    { "data": "manager.name" },
                    { "data": "installer.name" },
                    { "data": "object" },
                    { "data": "calculated_by.name" },
                    { "data": "", "orderable": false },
                ],
                columnDefs: [
                    {
                        targets: -1,
                        render: function (data, type, row) {
                            return '<a href="' + ('{{ route('admin.tools.bonus_calculator.show', '%id%') }}').replace('%id%', row['id']) + '" class="btn btn-sm btn-primary">{{ __('Show') }}</a>';
                        }
                    },
                ]
            });

            // filter datatable data by manager
            $('#manager-filter').on('change', function () {
                calculationsDataTable.columns(2).search(this.value).draw();
            });

            // filter datatable data by installer
            $('#installer-filter').on('change', function () {
                calculationsDataTable.columns(3).search(this.value).draw();
            });

            // get all dates by range from-to
            let dates = [];
            $('#date-from, #date-to').on('change', function () {
                let dateFrom = $('#date-from').val();
                let dateTo = $('#date-to').val();

                if (!dateFrom) {
                    // get min date from datatable
                    dateFrom = calculationsDataTable.column(1).data().min();
                }

                if (!dateTo) {
                    // get max date from datatable
                    dateTo = calculationsDataTable.column(1).data().max();
                }

                dates = [];

                // get dates between from-to
                let currentDate = new Date(dateFrom);
                let endDate = new Date(dateTo);

                while (currentDate <= endDate) {
                    dates.push(currentDate.toISOString().slice(0, 10));
                    currentDate.setDate(currentDate.getDate() + 1);
                }

                calculationsDataTable.columns(1).search(dates.join('|'), true, false).draw();
            });

            $('.input-daterange').datepicker({
                language: 'lt',
                weekStart: 1,
                todayHighlight: true,
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

        } );
    </script>
@endsection
