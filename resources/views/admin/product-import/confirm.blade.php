@extends('layouts.admin')

@section('title')
    {{ __('Review and confirm') }}
@endsection
@section('subtitle')
    {{ __('Duplicates will be merged') }}
@endsection
@section('actions')
    @include('components.admin.dashboard-action', ['route' => route('admin.product-import.do-import'), 'title' => __('Confirm and import'), 'class' => 'btn-warning', 'icon' => 'bi bi-filetype-csv'])
@endsection

@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                <tr class="bg-secondary text-white">
                    <th scope="col">{{ __('name') }}</th>
                    <th scope="col">{{ __('code') }}</th>
                    <th scope="col">{{ __('price') }}</th>
                    <th scope="col">{{ __('prime_cost') }}</th>
                    <th scope="col">{{ __('units') }}</th>
                    <th scope="col">{{ __('is_virtual') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($importQueue as $row)
                    <tr>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->code }}</td>
                        <td>{{ $row->price }}</td>
                        <td>{{ $row->prime_cost }}</td>
                        <td>{{ $row->units }}</td>
                        <td>{{ $row->is_virtual }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
