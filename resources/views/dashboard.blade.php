@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <h2>{{ __('Orders') }}</h2>
                            </div>
                            <div class="col-4 d-none d-lg-block">
                                <div class="btn-group float-end">
                                    <a href="{{ route('list.view.set', 'grid') }}" class="btn btn-outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grid" viewBox="0 0 16 16">
                                            <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('list.view.set', 'list') }}" class="btn btn-outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="actions-wrapper">
                            @role('customer')
                            <a href="{{ route('order.create') }}" class="btn btn-success">{{ __('New order') }}</a>
                            @endrole
                        </div>
                        <div class="orders-wrapper row gx-3 gy-3 pt-2">
                            @forelse($orders as $order)
                                <a href="{{ route('order.show', $order->id) }}" class="{{ session('list_view') }} text-decoration-none text-black">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-secondary text-white">
                                            {{ __('Order') }} {{ $order->number }}
                                            @role('admin', 'warehouse') ({{ $order->user->name }}, {{ $order->user->details->company_name }}) @endrole
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="row">
                                                <div class="col-5">
                                                    <p class="card-text mb-0">{{ __('Status') }}: {{ __($order->status->name) }}</p>
                                                    <p class="card-text mb-0">{{ __('Total') }}: {{ $order->total }}€</p>
                                                </div>
                                                <div class="col-7 text-end">
                                                    <p class="card-text mb-0">{{ __('Created') }}: {{ $order->created_at->format('Y-m-d H:i') }}</p>
                                                    <p class="card-text mb-0">{{ __('Updated') }}: {{ $order->updated_at->format('Y-m-d H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="p-2 ps-2 fs-5 text-secondary">
                                    {{ __('No orders') }}
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
