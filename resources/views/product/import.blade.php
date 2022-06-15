@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Import products') }}</h2>
                        <form method="post" action="{{ route('product.parsecsv') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="row">
                                    <div class="form-group mt-2 col-6">
                                        <label for="product_file" class="form-label">{{ __('Products CSV') }}</label>
                                        <input type="file" name="csv" id="product_file" class="form-control" accept=".csv" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('Upload') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
