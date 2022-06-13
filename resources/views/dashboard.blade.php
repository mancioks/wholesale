@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('My orders') }}</h2>
                        <div class="actions-wrapper">
                            <a href="{{ route('order.create') }}" class="btn btn-success">New order</a>
                        </div>
                        <table class="table mt-3">
                            <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Status</th>
                                <th scope="col">Total</th>
                                <th scope="col">Created</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($user->orders as $order)
                                <tr>
                                    <th scope="row">
                                        <a href="{{ route('order.show', $order->id) }}">#VD_000{{ $order->id }}</a>
                                    </th>
                                    <td>{{ $order->status->name }}</td>
                                    <td>{{ $order->total }}€</td>
                                    <td>{{ $order->created_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">{{ __('No orders') }}</td>
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
