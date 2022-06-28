@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Cart') }}</h2>
                        <form action="{{ route('cart.update') }}" method="post">
                            @csrf
                            @method('put')
                            <div class="products-wrapper row gx-2 gy-2">
                                @forelse(auth()->user()->cart as $cart_item)
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                                        <div class="card shadow-sm">
                                            <div class="card-body p-2">
                                                <div>
                                                    <a href="{{ route('cart.remove', $cart_item->id) }}" class="btn btn-sm btn-outline-danger float-end">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                                                        </svg>
                                                    </a>
                                                    <h5 class="card-title">{{ $cart_item->name }}</h5>
                                                    <p class="card-text mb-2">{{ $cart_item->price }}€</p>
                                                    <p class="card-text mb-2">{{ $cart_item->pivot->qty }} vnt - {{ $cart_item->amount }}€</p>
                                                </div>
                                                <div class="row">
                                                    <div>
                                                        <input type="hidden" value="{{ $cart_item->id }}" name="item[]">
                                                        <input type="number" value="{{ $cart_item->pivot->qty }}" name="qty[]" class="btn btn-light text-start remove-outline border-1 border-dark cursor-auto w-100">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-2 ps-2 fs-5 text-secondary">
                                        {{ __('Cart empty') }}
                                    </div>
                                @endforelse
                            </div>
                            <div class="cart-update mt-3">
                                @if(auth()->user()->cart()->exists())
                                    <div class="cart-update">
                                        <button type="submit" class="btn btn-warning">{{ __('Update') }}</button>
                                    </div>
                                    <div class="cart-total mt-3 mb-3">
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
