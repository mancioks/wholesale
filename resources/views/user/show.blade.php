@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-info-tab" data-bs-toggle="pill" data-bs-target="#pills-info" type="button" role="tab" aria-controls="pills-info" aria-selected="true">{{ __('User info') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-orders-tab" data-bs-toggle="pill" data-bs-target="#pills-orders" type="button" role="tab" aria-controls="pills-orders" aria-selected="false">{{ __('Orders') }}</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-info" role="tabpanel" aria-labelledby="pills-home-tab">
                                <h2 class="d-inline-block">{{ __('User') }}: {{ $user->name }}</h2>
                                <div class="d-inline-block">
                                    <a href="{{ route('user.edit', $user) }}" class="btn btn-primary btn-sm ms-3 d-inline-block mt-n2">{{ __('Edit') }}</a>
                                </div>
                                <table class="table mt-3">
                                    <tbody>
                                    <tr>
                                        <th scope="row">{{ __('Full name') }}</th>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Email') }}</th>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Role') }}</th>
                                        <td>{{ $user->role->name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Warehouse') }}</th>
                                        <td>
                                            @if($user->warehouse)
                                                {{ $user->warehouse->name }}
                                            @else
                                                {{ __('Not selected') }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('VAT') }}</th>
                                        <td>{{ $user->pvm_size }}%</td>
                                    </tr>
                                    @if($user->details)
                                        <tr>
                                            <th scope="row">{{ __('Company name') }}</th>
                                            <td>{{ $user->details->company_name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{ __('Address') }}</th>
                                            <td>{{ $user->details->address }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{ __('Registration code') }}</th>
                                            <td>{{ $user->details->registration_code }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{ __('VAT number') }}</th>
                                            <td>{{ $user->details->vat_number }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{ __('Phone number') }}</th>
                                            <td>{{ $user->details->phone_number }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{ __('Get email notifications') }}</th>
                                            <td>{{ $user->get_emails ? 'Enabled' : 'Disabled' }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <th scope="row">{{ __('Details') }}</th>
                                            <td>{{ __('Not provided') }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th scope="row">{{ __('Registered') }}</th>
                                        <td>{{ $user->created_at }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="pills-orders" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <div class="row">
                                    <div class="col-8">
                                        <h2>{{ $user->name }} {{ __('Orders') }}</h2>
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
                                <div class="orders-wrapper row gx-3 gy-3 pt-2">
                                    @foreach($user->orders as $order)
                                        <a href="{{ route('order.show', $order->id) }}" class="{{ session('list_view') }} text-decoration-none text-black">
                                            <div class="card shadow-sm">
                                                <div class="card-header {{ $order->list_class }} text-white">
                                                    {{ __('Order') }} {{ $order->number }}
                                                    @role('admin', 'warehouse', 'super_admin') ({{ $order->user->name }}, {{ $order->user->details->company_name }}) @endrole
                                                </div>
                                                <div class="card-body p-3 pb-0">
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
                                                <div class="order-products-thumbs p-3">
                                                    @foreach($order->items as $product)
                                                        <img src="{{ asset($product->image->name) }}" height="30px" class="rounded-1" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $product->name }} ({{ $product->qty }})"/>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                                @if($user->orders->isEmpty())
                                    <div class="p-2 ps-2 fs-5 text-black text-center bg-secondary bg-opacity-10 pt-5 pb-5 rounded-3 mt-3">
                                        {{ __('No orders') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
