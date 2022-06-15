@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Create product') }}</h2>
                        <form method="post" action="{{ route('product.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-3">
                                    <label for="product_name" class="form-label">{{ __('Product name') }}</label>
                                    <input type="text" class="form-control" id="product_name" name="name" value="{{ old('name') }}" required>
                                </div>
                                <div class="col-3">
                                    <label for="product_price" class="form-label">{{ __('Price') }}</label>
                                    <input type="number" class="form-control" id="product_price" name="price" step=".01" value="{{ old('price') }}" required>
                                </div>
                                <div class="col-3">
                                    <label for="product_units" class="form-label">{{ __('Units') }}</label>
                                    <input type="text" class="form-control" id="product_units" name="units" value="{{ old('units') }}" required>
                                </div>
                                <div class="row">
                                    <div class="form-group mt-2 col-6">
                                        <label for="product_file" class="form-label">{{ __('Photo') }}</label>
                                        <input type="file" name="image" id="product_file" class="form-control" accept=".jpg,.jpeg,.bmp,.png,.gif,.webp">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
