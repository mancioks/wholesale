@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body pb-0">
                        <div class="row">
                            <h2>{{ __('New order') }}</h2>
                        </div>
                        <div class="mb-3">
                            <form action="{{ route('order.create') }}" method="get" class="input-group rounded">
                                <input type="text" class="form-control rounded" placeholder="Search" name="query" value="{{ $search_query }}" />
                                <button type="submit" class="input-group-text border-0">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <div>
                            {{ $products->links('pagination::bootstrap-5') }}
                        </div>
                        @if($search_query)
                            <div class="mb-3">
                                <div class="h4 mb-0">
                                    Search results for: "{{ $search_query }}"
                                </div>
                                <div>
                                    <a class="small text-decoration-none text-primary" href="{{ route('order.create') }}">Show all</a>
                                </div>
                            </div>
                        @endif
                        <div class="products-wrapper row row-cols-3 gx-2 gy-2">
                            @forelse($products as $product)
                                <div class="col">
                                    <div class="card shadow-sm">
                                        <div class="card-body p-2">
                                            <div class="bg-light p-3 text-center mb-3 rounded">
                                                <img src="{{ asset($product->image->name) }}" class="card-img-top w-auto" style="height: 150px;">
                                            </div>
                                            <h5 class="card-title">{{ $product->name }}</h5>
                                            <p class="card-text mb-2">{{ $product->price }}€ / {{ $product->units }}</p>
                                            <form action="{{ route('cart.add', $product->id) }}" method="post">
                                                @csrf
                                                <input type="number" name="qty" value="1">
                                                <button type="submit" class="btn btn-dark w-100">{{ __('Add to cart') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div>{{ __('No products found') }}</div>
                            @endforelse
                        </div>
                        <div class="mt-4 mb-1">
                            {{ $products->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Cart') }}</h2>
                        <form action="{{ route('cart.update') }}" method="post">
                            @csrf
                            @method('put')
                            <ul class="list-group">
                                @forelse(auth()->user()->cart as $cart_item)
                                    <li class="list-group-item">
                                        <div class="cart-item-name">
                                            {{ $cart_item->name }}
                                        </div>
                                        <div class="cart-item-qty">
                                            <input type="hidden" value="{{ $cart_item->id }}" name="item[]">
                                            <input type="number" value="{{ $cart_item->pivot->qty }}" name="qty[]">
                                        </div>
                                        <div class="cart-item-remove">
                                            <a href="{{ route('cart.remove', $cart_item->id) }}">{{ __('Remove') }}</a>
                                        </div>
                                        <div class="cart-item-subtotal">
                                            {{ $cart_item->amount }}€
                                        </div>
                                    </li>
                                @empty
                                    <li class="list-group-item">{{ __('Empty') }}</li>
                                @endforelse
                            </ul>
                            <div class="cart-update">
                                @if(auth()->user()->cart()->exists())
                                    <div class="cart-update">
                                        <button type="submit" class="btn btn-warning">{{ __('Update') }}</button>
                                    </div>
                                    <div class="cart-total">
                                        {{ __('Subtotal') }}: {{ auth()->user()->sub_total }}€
                                    </div>
                                    <div class="cart-proceed">
                                        <a href="{{ route('order.review') }}" class="btn btn-success d-block">{{ __('Proceed') }}</a>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
