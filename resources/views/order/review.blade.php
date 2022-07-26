@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Order review') }}</h2>
                        @if(auth()->user()->cart()->exists())
                            <div class="order-review">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{ __('Product') }}</th>
                                        <th scope="col">{{ __('Quantity') }}</th>
                                        <th scope="col">{{ __('Price') }}</th>
                                        <th scope="col">{{ __('Amount') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach(auth()->user()->cart as $item)
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->pivot->qty }}</td>
                                            <td>{{ $item->price }}€</td>
                                            <td>{{ $item->amount }}€</td>
                                        </tr>
                                    @endforeach
                                    <tr class="fw-bold">
                                        <td colspan="3" class="text-end border-0">{{ __('Subtotal') }}</td>
                                        <td class="bg-light">{{ auth()->user()->sub_total }}€</td>
                                    </tr>
                                    <tr class="fw-bold">
                                        <td colspan="3" class="text-end border-0">{{ __('Tax') }}</td>
                                        <td class="bg-light">{{ auth()->user()->pvm_size }}%</td>
                                    </tr>
                                    <tr class="fw-bold">
                                        <td colspan="3" class="text-end border-0">{{ __('Total') }}</td>
                                        <td class="bg-light">{{ auth()->user()->total }}€</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <form action="{{ route('order.confirm') }}" method="post">
                                @csrf
                                <div class="payment-method">
                                    <h4>{{ __('Payment method') }}</h4>
                                    @foreach($payment_methods as $payment_method)
                                        <input type="radio" class="btn-check" name="payment_method" value="{{ $payment_method->id }}" id="option{{ $loop->index }}" autocomplete="off" {{ $loop->first ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success" for="option{{ $loop->index }}">{{ $payment_method->name }}</label>
                                    @endforeach
                                </div>
                                <div class="warehouse-method mt-3">
                                    <h4>{{ __('Warehouse') }}</h4>
                                    @foreach($warehouses as $warehouse)
                                        <input type="radio" class="btn-check" name="warehouse_id" value="{{ $warehouse->id }}" id="warehouse{{ $loop->index }}" autocomplete="off" {{ $loop->first ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary text-start" for="warehouse{{ $loop->index }}">
                                            <b>{{ $warehouse->name }}</b><br>
                                            {{ $warehouse->address }}
                                        </label>
                                    @endforeach
                                </div>
                                <div class="add-comment-to-order mt-3">
                                    <h4>{{ __('Leave a message') }}</h4>
                                    <textarea placeholder="{{ __('Message') }}" class="form-control" name="message" rows="3"></textarea>
                                </div>
                                <div class="order-submit mt-3">
                                    @if(auth()->user()->details()->exists())
                                    <button type="submit" class="btn btn-warning d-block w-100">{{ __('Confirm') }}</button>
                                    @endif
                                </div>
                            </form>
                        @else
                            <div class="alert alert-warning">{{ __('Cart empty') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
