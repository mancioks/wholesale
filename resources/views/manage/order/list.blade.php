@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Orders') }}</h2>
                        <table class="table mt-3">
                            <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">User</th>
                                <th scope="col">Status</th>
                                <th scope="col">Total</th>
                                <th scope="col">Created</th>
                                <th scope="col">Updated</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <th scope="row">
                                        <a href="{{ route('manage.order.show', $order->id) }}">{{ $order->number }}</a>
                                    </th>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->status->name }}</td>
                                    <td>{{ $order->total }}€</td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>{{ $order->updated_at }}</td>
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
