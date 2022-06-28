@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Products') }}</h2>
                        <div class="actions-wrapper">
                            <a href="{{ route('product.create') }}" class="btn btn-success">{{ __('Create product') }}</a>
                            <a href="{{ route('product.import') }}" class="btn btn-warning">{{ __('Import products') }}</a>
                        </div>
                        <table class="table mt-3">
                            <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">{{ __('Title') }}</th>
                                <th scope="col">{{ __('Price') }}</th>
                                <th scope="col">{{ __('Image') }}</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <th scope="row">{{ $product->id }}</th>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>
                                        <img src="{{ asset($product->image->name) }}" class="card-img-top w-auto" style="height: 30px;">
                                    </td>
                                    <td>
                                        <a href="{{ route('product.edit', $product->id) }}" class="btn btn-primary btn-sm d-inline-block">{{ __('Edit') }}</a>
                                        <form method="post" action="{{ route('product.destroy', $product->id) }}" class="d-inline-block" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-sm d-inline-block">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">{{ __('No products') }}</td>
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
