@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <h2>{{ __('Order ') }} {{ $order->number }} {{ __('created!') }}</h2>
                        <div class="row">
                            <div class="col-6">
                                <h3>Rekvizitai:</h3>
                                <p>{{ setting('company.details') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Payment details') }}</h2>
                        asd
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
