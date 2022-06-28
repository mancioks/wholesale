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
