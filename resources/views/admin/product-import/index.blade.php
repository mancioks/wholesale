@extends('layouts.admin')

@section('title')
    {{ __('Product import') }}
@endsection

@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <form method="post" action="{{ route('admin.product-import.parse-csv') }}" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-lg-5">
                        <label for="product_file" class="form-label">{{ __('Products CSV') }}</label>
                        <input type="file" name="csv" id="product_file" class="form-control" accept=".csv,.xlsx" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Upload') }}</button>
            </form>
        </div>
    </div>
@endsection
