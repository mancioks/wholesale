@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Review and confirm') }}</h2>
                        <table class="table mt-3">
                            <thead class="table-dark">
                            <tr>
                                <th scope="col">{{ __('name') }}</th>
                                <th scope="col">{{ __('price') }}</th>
                                <th scope="col">{{ __('units') }}</th>
                                <th scope="col">{{ __('prime_cost') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($importQueue as $row)
                                <tr>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->price }}</td>
                                    <td>{{ $row->units }}</td>
                                    <td>{{ $row->prime_cost }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        {{ __('Duplicates will be merged') }}
                        <div class="mt-2">
                            <a href="{{ route('product.doimport') }}" class="btn btn-warning">{{ __('Confirm and import') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
