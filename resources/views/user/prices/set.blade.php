@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Price assignment') }}</h2>
                        <form method="post" action="{{ route('user.prices.assign', [$user, $product]) }}">
                            @csrf
                            @method('put')
                            <div class="row mb-3">
                                <div class="col-lg-5">
                                    <label for="user_name" class="form-label">{{ __('User') }}</label>
                                    <input type="text" class="form-control" id="user_name" value="#{{ $user->id }} {{ $user->name }} ({{ $user->email }})" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-5">
                                    <label for="product_name" class="form-label">{{ __('Product') }}</label>
                                    <input type="text" class="form-control" id="product_name" value="#{{ $product->id }} {{ $product->name }}" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-5">
                                    <label for="product_price" class="form-label">{{ __('Product price') }}</label>
                                    <input type="text" class="form-control" id="product_price" value="{{ $product->original_price }}" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-5">
                                    <label for="user_price" class="form-label">{{ __('User price') }}</label>
                                    <input type="text" class="form-control" id="user_price" name="user_price" value="{{ $userPrice ? $userPrice->pivot->price : $product->original_price }}" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('Assign') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
