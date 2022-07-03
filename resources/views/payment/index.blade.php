@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Payments') }}</h2>
                        <table class="table mt-3">
                            <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">{{ __('Order number') }}</th>
                                <th scope="col">{{ __('Amount') }}</th>
                                <th scope="col">{{ __('Method') }}</th>
                                <th scope="col">{{ __('Date') }}</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <th scope="row">{{ $payment->id }}</th>
                                    <td>{{ $payment->order->number }}</td>
                                    <td>{{ price_format($payment->amount) }}€</td>
                                    <td>{{ $payment->method->name }}€</td>
                                    <td>{{ $payment->created_at }}</td>
                                    <td>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">{{ __('No payments') }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
