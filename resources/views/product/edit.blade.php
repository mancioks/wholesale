@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2 class="d-inline-block">{{ __('Edit product') }}</h2>
                        <form method="post" action="{{ route('product.destroy', $product->id) }}" class="d-inline-block" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger btn-sm ms-3 d-inline-block mt-n2">{{ __('Delete') }}</button>
                        </form>
                        <div class="row">
                            <div class="col-lg-6">
                                <form method="post" action="{{ route('product.update', $product->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="product_name" class="form-label">{{ __('Product name') }}</label>
                                            <input type="text" class="form-control" id="product_name" name="name" value="{{ $product->name }}" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="product_price" class="form-label">{{ __('Price') }} ({{ __('Without VAT') }})</label>
                                            <input type="number" class="form-control" id="product_price" name="price" step=".01" value="{{ $product->original_price }}" required>
                                        </div>
                                    </div>
                                    @role('warehouse', 'customer', 'admin') <div class="d-none"> @endrole
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="product_prime_cost" class="form-label">{{ __('Prime cost') }}</label>
                                                <input type="number" class="form-control" id="product_prime_cost" name="prime_cost" step=".01" value="{{ $product->prime_cost ?: 0 }}" required>
                                            </div>
                                        </div>
                                        @role('warehouse', 'customer', 'admin') </div> @endrole
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="product_units" class="form-label">{{ __('Units') }}</label>
                                            <input type="text" class="form-control" id="product_units" name="units" value="{{ $product->units }}" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col position-relative" id="product_photo">
                                            <div class="card shadow-sm">
                                                <div class="card-body p-2">
                                                    <div class="bg-light p-3 text-center rounded">
                                                        <img src="{{ asset($product->image->name) }}" class="card-img-top w-auto" style="height: 150px;">
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="#" class="text-danger position-absolute bottom-0 start-0 ps-4 pb-1"
                                               onclick="document.getElementById('product_photo').classList.add('d-none');
                                            document.getElementById('product_photo_form').classList.remove('d-none');
                                            document.getElementById('product_file').click()">
                                                Change photo
                                            </a>
                                        </div>
                                        <div class="form-group col-lg-5 d-none" id="product_photo_form">
                                            <label for="product_file" class="form-label">{{ __('Photo') }}</label>
                                            <input type="file" name="image" id="product_file" class="form-control" accept=".jpg,.jpeg,.bmp,.png,.gif,.webp">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">{{ __('Update product info') }}</button>
                                </form>
                            </div>
                            <div class="col-lg-6 mt-4">
                                <form method="post" action="{{ route('product.update.warehouses', $product) }}">
                                    @csrf
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col">{{ __('Warehouse') }}</th>
                                            <th scope="col">{{ __('Price') }}</th>
                                            <th scope="col">{{ __('Can buy') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody class="border-top-0">
                                            @foreach($product->warehouses as $warehouse)
                                                <tr>
                                                    <th scope="row">{{ $warehouse->name }}</th>
                                                    <td>
                                                        <input class="form-control form-control-sm w-50 d-inline-block" type="number" name="warehouse[{{ $warehouse->id }}][price]" min="0" step=".01" value="{{ $warehouse->pivot->price ?: $product->original_price }}"/>
                                                        @if($warehouse->pivot->price)
                                                            <div class="badge bg-danger text-white d-inline-block">{{ __('Fixed') }}</div>
                                                        @else
                                                            <div class="badge bg-primary text-white d-inline-block">{{ __('Inherited') }}</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" value="1" name="warehouse[{{ $warehouse->id }}][enabled]" {{ $warehouse->pivot->enabled ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-primary">{{ __('Update warehouses settings') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
