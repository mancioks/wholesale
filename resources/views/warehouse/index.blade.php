@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Warehouses') }}</h2>
                        <div class="actions-wrapper">
                            <a href="{{ route('warehouse.create') }}" class="btn btn-success">{{ __('Add warehouse') }}</a>
                        </div>
                        <table class="table mt-3">
                            <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Active') }}</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($warehouses as $warehouse)
                                <tr>
                                    <th scope="row">{{ $warehouse->id }}</th>
                                    <td>{{ $warehouse->name }}</td>
                                    <td>{{ $warehouse->active }}</td>
                                    <td>
                                        <a href="{{ route('warehouse.edit', $warehouse->id) }}" class="btn btn-primary btn-sm d-inline-block">{{ __('Edit') }}</a>
                                        <form method="post" action="{{ route('warehouse.destroy', $warehouse->id) }}" class="d-inline-block" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-sm d-inline-block">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">{{ __('No warehouses') }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
