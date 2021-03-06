@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Order ') }} {{ $order->number }}</h2>
                        <div class="products-wrapper row row-cols-3 gx-2 gy-2">
                            @foreach($order->items as $product)
                                <div class="col">
                                    <div class="card shadow-sm">
                                        <div class="card-body p-2">
                                            <div class="bg-light p-3 text-center mb-3 rounded">
                                                <img src="{{ asset($product->image->name) }}" class="card-img-top w-auto" style="height: 150px;">
                                            </div>
                                            <h5 class="card-title">{{ $product->name }}</h5>
                                            <div class="row">
                                                <div class="col-5">
                                                    <p class="card-text mb-0">{{ $product->price }}€</p>
                                                </div>
                                                <div class="col-7 text-end">
                                                    {{ $product->qty }} {{ $product->product->units }} - {{ $product->amount }}€
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Overview') }}</h2>
                        <div>
                            <div class="d-flex align-items-center">
                                <h5>Order status:</h5>
                                <span class="badge bg-primary rounded-pill ms-2">{{ $order->status->name }}</span>
                            </div>
                            <div>
                                @foreach($order->actions as $status => $action)
                                    <a href="{{ route('manage.order.set.status', [$order, $status]) }}" class="btn btn-danger btn">{{ $action }}</a>
                                @endforeach
                            </div>
                            <div class="d-flex align-items-center">
                                <h5>Total:</h5>
                                <span class="badge bg-primary rounded-pill ms-2">{{ $order->total }} €</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
