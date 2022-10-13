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
                                        <div class="btn btn-outline-dark text-start mb-1 @if(auth()->user()->warehouse->id === $warehouse->id) bg-dark text-white @else disabled opacity-25 @endif ">
                                            <b>{{ $warehouse->name }}</b><br>
                                            {{ $warehouse->address }}
                                        </div>
                                    @endforeach
                                </div>
                                <div class="add-comment-to-order mt-3">
                                    <h4>{{ __('Leave a message') }}</h4>
                                    <textarea placeholder="{{ __('Message') }}" class="form-control" name="message" rows="3"></textarea>
                                </div>
                                <div class="pre-invoice-required mt-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="pre_invoice_required" role="switch" id="pre-invoice-required">
                                        <label class="form-check-label" for="pre-invoice-required">{{ __('Pre-invoice required') }}</label>
                                    </div>
                                    <div id="customer-details-form" class="pt-3 d-none">
                                        <div class="row">
                                            <div class="col-lg-3 mb-3">
                                                <label class="form-label">{{ __('Full name') }}</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    name="name"
                                                    value="{{ $user->name }}"
                                                />
                                            </div>
                                            <div class="col-lg-3 mb-3">
                                                <label class="form-label">{{ __('Email') }}</label>
                                                <input
                                                    type="email"
                                                    class="form-control"
                                                    name="email"
                                                    value="{{ $user->email }}"
                                                />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 mb-3">
                                                <label class="form-label">{{ __('Company name') }}</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    name="company_name"
                                                    value="{{ $user->details ? $user->details->company_name : '' }}"
                                                />
                                            </div>
                                            <div class="col-lg-3 mb-3">
                                                <label class="form-label">{{ __('Address') }}</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    name="address"
                                                    value="{{ $user->details ? $user->details->address : '' }}"
                                                />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 mb-3">
                                                <label class="form-label">{{ __('Registration code') }}</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    name="registration_code"
                                                    value="{{ $user->details ? $user->details->registration_code : '' }}"
                                                />
                                            </div>
                                            <div class="col-lg-3 mb-3">
                                                <label class="form-label">{{ __('VAT number') }}</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    name="vat_number"
                                                    value="{{ $user->details ? $user->details->vat_number : '' }}"
                                                />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 mb-3">
                                                <label class="form-label">{{ __('Phone number') }}</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    name="phone_number"
                                                    value="{{ $user->details ? $user->details->phone_number : '' }}"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="order-submit mt-3">
                                    <button type="submit" class="btn btn-warning d-block w-100">{{ __('Confirm') }}</button>
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
    <script>
        var invoiceToOtherCheckBox = document.getElementById('pre-invoice-required');
        var customerDetailsForm = document.getElementById('customer-details-form');

        invoiceToOtherCheckBox.addEventListener('click', function () {
            if(invoiceToOtherCheckBox.checked) {
                customerDetailsForm.classList.remove('d-none');
            } else {
                customerDetailsForm.classList.add('d-none');
            }
        });
    </script>
@endsection
