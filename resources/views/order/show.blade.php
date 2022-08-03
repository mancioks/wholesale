@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 order-last order-lg-first mt-3 mt-lg-0">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Order') }} {{ $order->number }}</h2>
                        @role('warehouse', 'admin', 'super_admin')
                            <p>{{ $order->customer_name }}, {{ $order->customer_company_name }} {{ $order->customer_company_phone_number }} {{ $order->customer_email }}</p>
                        @endrole
                        <div class="products-wrapper row gx-2 gy-2">
                            @foreach($order->items as $product)
                                <div class="col-lg-4 col-md-6">
                                    <div class="card shadow-sm">
                                        <div class="card-body p-2">
                                            <div class="bg-light p-2 text-center mb-3 rounded product-img-wrapper">
                                                <img src="{{ asset($product->image->name) }}">
                                            </div>
                                            <h5 class="card-title">{{ $product->name }}</h5>
                                            <div class="row">
                                                <div class="col-5">
                                                    <p class="card-text mb-0">{{ $product->price }}€</p>
                                                </div>
                                                <div class="col-7 text-end">
                                                    {{ $product->qty }} {{ $product->units }} - {{ $product->amount }}€
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow shadow">
                    <div class="card-header {{ $order->list_class }} text-white pe-2">
                        <span class="d-inline-block pt-1">
                            <i class="bi bi-info-circle"></i> {{ __($order->status->name) }}
                        </span>
                        <div class="float-end">
                            @if($order->actions)
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-pencil-square"></i> {{ __('Actions') }}
                                    </button>
                                    <ul class="dropdown-menu">
                                        @foreach($order->actions as $status => $action)
                                            <li><a href="{{ route('order.set.status', [$order, $status]) }}" class="dropdown-item">{{ __($action) }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div>
                            @if($order->warehouse()->exists())
                                <div class="mt-1 mb-3">
                                    <div class="border border-1 border-primary rounded p-2 ps-3 pe-3 text-primary d-inline-block">
                                        <b>{{ $order->warehouse->name }}</b><br>
                                        {{ $order->warehouse->address }}
                                    </div>
                                </div>
                            @endif
                            @if($order->vat_invoice)
                                <div class="mt-2">
                                    <a href="{{ route('vat.invoice', $order) }}" class="btn btn-outline-danger" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-pdf" viewBox="0 0 16 16">
                                            <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                                            <path d="M4.603 12.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.701 19.701 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.187-.012.395-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.065.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.716 5.716 0 0 1-.911-.95 11.642 11.642 0 0 0-1.997.406 11.311 11.311 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.27.27 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.647 12.647 0 0 1 1.01-.193 11.666 11.666 0 0 1-.51-.858 20.741 20.741 0 0 1-.5 1.05zm2.446.45c.15.162.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.881 3.881 0 0 0-.612-.053zM8.078 5.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z"/>
                                        </svg>
                                        {{ __('VAT invoice') }}
                                    </a>
                                </div>
                            @else
                                @if(!$order->is_canceled)
                                    <div class="mt-2">
                                        <a href="{{ route('invoice', $order) }}" class="btn btn-outline-dark" target="_blank">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-pdf" viewBox="0 0 16 16">
                                                <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                                                <path d="M4.603 12.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.701 19.701 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.187-.012.395-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.065.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.716 5.716 0 0 1-.911-.95 11.642 11.642 0 0 0-1.997.406 11.311 11.311 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.27.27 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.647 12.647 0 0 1 1.01-.193 11.666 11.666 0 0 1-.51-.858 20.741 20.741 0 0 1-.5 1.05zm2.446.45c.15.162.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.881 3.881 0 0 0-.612-.053zM8.078 5.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z"/>
                                            </svg>
                                            {{ __('Pre-Invoice') }}
                                        </a>
                                    </div>
                                @endif
                            @endif
                            <div class="mt-3 mb-0">
                                {{ __('Total') }}: <b>{{ $order->total }} €</b>
                            </div>
                            <div>
                                {{ __('Payment method') }}: <b>{{ $order->paymentMethod->name }}</b>
                            </div>
                            <div class="mt-2 mb-0">
                                {{ __('Created') }}: <b>{{ $order->created_at }}</b>
                            </div>
                            <div>
                                {{ __('Updated') }}: <b>{{ $order->updated_at }}</b>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-secondary text-white pe-2">
                        <span class="d-inline-block pt-1">
                            <i class="bi bi-credit-card-2-front"></i> {{ __($order->paymentStatus->name) }}
                        </span>
                        <div class="float-end">
                            @role('admin', 'super_admin')
                            <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#paymentForm">
                                <i class="bi bi-cash-coin"></i> {{ __('Payments') }}
                            </button>
                            <div class="modal fade" id="paymentForm" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg text-black">
                                    <div class="modal-content">
                                        <form action="{{ route('payments.store', $order) }}" method="post">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">{{ __('Payments') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table mt-3">
                                                    <thead class="table-dark">
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">{{ __('Method') }}</th>
                                                        <th scope="col">{{ __('Date') }}</th>
                                                        <th scope="col">{{ __('Amount') }}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @forelse($order->payments as $payment)
                                                        <tr>
                                                            <th scope="row">{{ $loop->index + 1 }}</th>
                                                            <td>{{ $payment->method->name }}</td>
                                                            <td>{{ $payment->created_at }}</td>
                                                            <td>{{ price_format($payment->amount) }}€</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4">{{ __('No payments') }}</td>
                                                        </tr>
                                                    @endforelse
                                                    <tr>
                                                        <td colspan="3" class="border-0"></td>
                                                        <td><b>{{ __('Paid') }}</b>: {{ price_format($order->paid_total) }}€</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" class="border-0"></td>
                                                        <td><b>{{ __('Left to pay') }}</b>: {{ price_format($order->total - $order->paid_total) }}€</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label for="amount" class="col-form-label">{{ __('Amount') }}</label>
                                                        <input type="number" id="amount" class="form-control" name="amount" placeholder="{{ $order->total }}" step=".01">
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label for="payment_method" class="col-form-label">{{ __('Payment method') }}</label>
                                                        <select class="form-select" name="payment_method_id" id="payment_method">
                                                            @foreach($paymentMethods as $paymentMethod)
                                                                <option value="{{ $paymentMethod->id }}" {{ $paymentMethod->id === $order->paymentMethod->id ? 'selected':'' }}>{{ $paymentMethod->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                <button type="submit" class="btn btn-primary">{{ __('Add') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endrole
                        </div>
                    </div>
                </div>
                @if($order->message)
                    <div class="card mt-3 shadow shadow-sm">
                        <div class="card-header bg-warning">{{ __('Message') }}</div>
                        <div class="card-body">
                            {{ $order->message }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
