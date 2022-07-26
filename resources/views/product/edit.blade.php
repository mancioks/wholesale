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
                        <form method="post" action="{{ route('product.update', $product->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="row mb-3">
                                <div class="col-lg-5">
                                    <label for="product_name" class="form-label">{{ __('Product name') }}</label>
                                    <input type="text" class="form-control" id="product_name" name="name" value="{{ $product->name }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-5">
                                    <label for="product_price" class="form-label">{{ __('Price') }} ({{ __('Without VAT') }})</label>
                                    <input type="number" class="form-control" id="product_price" name="price" step=".01" value="{{ $product->price }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-5">
                                    <label for="product_units" class="form-label">{{ __('Units') }}</label>
                                    <input type="text" class="form-control" id="product_units" name="units" value="{{ $product->units }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-5 position-relative" id="product_photo">
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
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
