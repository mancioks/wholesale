@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Orders') }}</h2>
                        <div class="actions-wrapper">
                            @role('customer')
                            <a href="{{ route('order.create') }}" class="btn btn-success">New order</a>
                            @endrole
                        </div>
                        <table class="table mt-3">
                            <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                @role('admin', 'warehouse')
                                <th scope="col">User</th>
                                @endrole
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
                                        <a href="{{ route('order.show', $order->id) }}">{{ $order->number }}</a>
                                    </th>
                                    @role('admin', 'warehouse')
                                    <td>
                                        {{ $order->user->name }}<br>
                                        {{ $order->user->details->company_name }}
                                    </td>
                                    @endrole
                                    <td>{{ $order->status->name }}</td>
                                    <td>{{ $order->total }}€</td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>{{ $order->updated_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="@role('admin', 'warehouse') 6 @else 5 @endrole">{{ __('No orders') }}</td>
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
