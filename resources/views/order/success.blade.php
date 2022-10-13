@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <h2>{{ __('Order') }} {{ $order->number }} {{ __('created') }}!</h2>
                        <div class="row">
                            <div class="col-lg-6">
                                @if ($order->pre_invoice_required)
                                    <p>{{ __('Pre-invoice has been sent to you by email') }} ({{ $order->user->email }})</p>
                                @endif
                                <p>{{ __('Payment method') }}: <b>{{ $order->paymentMethod->name }}</b></p>
                                @if($order->paymentMethod->key == 'bank_transfer')
                                    <h3>{{ __('Company details') }}</h3>
                                    <p>{!! nl2br(setting('company.details')) !!}</p>
                                    <p>{{ __('When making bank transfer, define order number in payment destination') }}: <b>{{ $order->number }}</b></p>
                                @endif
                                <a href="{{ route('order.show', $order) }}" class="btn btn-primary">{{ __('Show order') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
