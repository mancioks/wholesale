@extends('layouts.admin')

@section('title')
    @if($order->order_type === config('constants.ORDER_TYPE_NORMAL'))
        {{ __('Order') }}
    @else
        {{ __('Issue') }}
    @endif
    {{ $order->number }}
@endsection
@section('subtitle')
    {{ $order->user->name }}, {{ $order->user->email }}
@endsection
@section('actions')
    @if($order->actions)
        <div class="dropdown d-inline-block">
            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-pencil-square"></i> {{ __('Actions') }}
            </button>
            <ul class="dropdown-menu">
                @foreach($order->actions as $status => $action)
                    <li><a href="{{ route('order.set.status', [$order, $status]) }}" class="dropdown-item">{{ __($action) }}</a></li>
                @endforeach
            </ul>
        </div>
    @endif
    @role('admin', 'super_admin', 'warehouse')
        @include('components.admin.modals.edit-order', ['order' => $order])
        @include('components.admin.modals.shortage', ['order' => $order])
        <form method="post" action="{{ route('order.destroy', $order) }}" class="d-inline-block" onsubmit="return confirm('{{ __('Are you sure?') }}')">
            @csrf
            @method('delete')
            <button type="submit" class="btn btn-sm btn-danger d-inline-block">
                <i class="bi bi-trash3-fill"></i> {{ __('Delete') }}
            </button>
        </form>
    @endrole
    @role('admin', 'super_admin')
        @include('components.admin.modals.payments', ['order' => $order, 'paymentMethods' => $paymentMethods])
    @endrole
    @if(!$order->is_canceled && $order->pre_invoice_required)
        <a href="{{ route('invoice', $order) }}" class="btn btn-sm btn-outline-dark" target="_blank">
            <i class="bi bi-file-pdf"></i> {{ __('Pre-Invoice') }}
        </a>
    @endif
    @if(!$order->is_canceled && $order->signature && $order->waybill_required)
        <a href="{{ route('waybill', $order) }}" class="btn btn-sm btn-outline-dark" target="_blank">
            <i class="bi bi-truck"></i> {{ __('Waybill') }}
        </a>
    @endif
    <a href="{{ route('summary', $order) }}" class="btn btn-sm btn-outline-dark" target="_blank">
        <i class="bi bi-archive"></i> {{ __('Summary') }}
    </a>
@endsection
@section('content')
    @if($order->status->id === constant('\App\Models\Status::PREPARED') && !$order->signature)
        <div class="mt-3">
            <div class="row">
                <div class="col">
                    <div class="shadow-sm card bg-warning bg-opacity-25">
                        <div class="card-body">
                            <span class="d-inline-block me-2">{{ __('Order must be signed') }}</span> @include('components.admin.modals.sign', ['order' => $order])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="mt-3">
        <div class="row justify-content-center">
            <div class="col-lg-8 order-last order-lg-first mt-3 mt-lg-0">
                <div class="shadow-sm card">
                    <div class="card-body">
                        <div class="products-wrapper row gx-2 gy-2">
                            @forelse($order->items as $product)
                                <div class="col-lg-4 col-md-6">
                                    <div class="card shadow-sm {{ !$product->shortage ?: 'border-danger' }}">
                                        <div class="card-body p-2">
                                            <div class="bg-light p-2 text-center mb-3 rounded product-img-wrapper">
                                                <img src="{{ asset($product->image->name) }}">
                                            </div>
                                            <h5 class="card-title">
                                                {{ $product->name }}
                                            </h5>
                                            <div class="row">
                                                <div class="col-5">
                                                    <p class="card-text mb-0">
                                                        {{ $product->priceWithPvm }}€
                                                        @role('super_admin')
                                                        <i class="bi bi-coin text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Prime cost') }}: {{ $product->prime_cost ?: 0 }}€"></i>
                                                        @endrole
                                                    </p>
                                                </div>
                                                <div class="col-7 text-end">
                                                    @if($product->shortage)
                                                        <i class="bi bi-exclamation-triangle text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ sprintf(__('%s %s missing from the warehouse (%s left)'), $product->shortage, $product->units, $product->stock) }}"></i>
                                                    @endif
                                                    {{ $product->qty }} {{ $product->units }} - {{ $product->amount }}€
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-2 ps-2 fs-5 text-black text-center bg-secondary bg-opacity-10 pt-5 pb-5 rounded-3 mt-1">
                                    {{ __('No products') }}
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @if($order->signature)
                    <div class="shadow-sm card mt-3">
                        <div class="card-header bg-success text-white">
                            {{ __('Signed') }}
                        </div>
                        <div class="card-body">
                            <div class="d-inline-block border-bottom pb-2 border-secondary">
                                <span class="align-bottom">{{ $order->user->name }}</span>
                                <img src="{{ asset($order->signature) }}" class="align-bottom" height="70">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-lg-4">
                <div class="card shadow shadow">
                    <div class="card-header {{ $order->list_class }} text-white pe-2">
                        <span class="d-inline-block pt-1">
                            <i class="bi bi-info-circle"></i> {{ __($order->status->name) }}
                        </span>
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
                            <div class="mt-3 mb-0">
                                {{ __('Total') }}: <b>{{ $order->total }} €</b>
                            </div>
                            @if($order->paymentMethod)
                                <div>
                                    {{ __('Payment method') }}: <b>{{ $order->paymentMethod->name }}</b>
                                </div>
                            @endif
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
                @if($order->created_by)
                    <div class="card mt-3 shadow shadow-sm">
                        <div class="card-header bg-secondary text-white">{{ __('Created by') }}</div>
                        <div class="card-body">
                            {{ $order->createdByUser->name }}
                        </div>
                    </div>
                @endif
                @if($order->pre_invoice_required)
                    <div class="card mt-3 shadow shadow-sm">
                        <div class="card-header bg-secondary text-white">{{ __('Props') }}</div>
                        <div class="card-body">
                            {!! $order->customer_name !== '' ? __('Full name').': '.$order->customer_name.'<br>' : '' !!}
                            {!! $order->customer_email !== '' ? __('Email').': '.$order->customer_email.'<br>' : '' !!}
                            {!! $order->customer_company_name !== '' ? __('Company name').': '.$order->customer_company_name.'<br>' : '' !!}
                            {!! $order->customer_company_address !== '' ? __('Address').': '.$order->customer_company_address.'<br>' : '' !!}
                            {!! $order->customer_company_registration_code !== '' ? __('Registration code').': '.$order->customer_company_registration_code.'<br>' : '' !!}
                            {!! $order->customer_company_vat_number !== '' ? __('VAT number').': '.$order->customer_company_vat_number.'<br>' : '' !!}
                            {!! $order->customer_company_phone_number !== '' ? __('Phone number').': '.$order->customer_company_phone_number.'<br>' : '' !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            window.livewire.on('orderUpdated', () => {
                setTimeout(() => {
                    $('#edit-order-modal').modal('hide');
                    location.reload();
                }, 1000);
            });
        });
    </script>
@endsection
