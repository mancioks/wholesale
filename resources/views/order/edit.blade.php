@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ route('order.update', $order) }}" method="post">
            @csrf
            <div class="row justify-content-center">
                <div class="col-lg-8 order-last order-lg-first mt-3 mt-lg-0">
                    <div class="card">
                        <div class="card-body">
                            <h2>{{ sprintf(__('Order %s editing'), $order->number) }}</h2>
                            @role('warehouse', 'admin', 'super_admin')
                                <p>{{ $order->customer_name }}, {{ $order->customer_company_name }} {{ $order->customer_company_phone_number }} {{ $order->customer_email }}</p>
                            @endrole
                            <div>
                                {{ __('Select product you want to add') }}:
                            </div>
                            <div class="mb-3 mt-1">
                                <div class="position-relative">
                                    <div class="input-group rounded">
                                        <input type="text" class="form-control rounded" autocomplete="off" placeholder="{{ __('Search') }}" name="query" id="productSearch" />
                                    </div>
                                    <div class="position-absolute start-0 end-0 bg-light p-2 mt-1 rounded border border-white shadow d-none zindex-10" id="searchSuggestions"></div>
                                </div>
                            </div>
                            <div class="products-wrapper row gx-2 gy-2">
                                @forelse($order->items as $product)
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card shadow-sm position-relative">
                                            <div class="card-body p-2">
                                                <div class="bg-light p-2 text-center mb-3 rounded product-img-wrapper">
                                                    <img src="{{ asset($product->image->name) }}">
                                                </div>
                                                <h5 class="card-title">
                                                    {{ $product->name }}
                                                </h5>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <p class="card-text mb-2">
                                                            <input class="form-control form-control-sm w-50 d-inline-block" type="number" step=".01" min="0" name="product[{{ $product->id }}][price]" value="{{ $product->price }}"> €
                                                            @role('super_admin')
                                                                <i class="bi bi-coin text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Prime cost') }}: {{ $product->prime_cost ?: 0 }}€"></i>
                                                            @endrole
                                                        </p>
                                                    </div>
                                                    <div class="col-12">
                                                        <input class="form-control form-control-sm w-50 d-inline-block" type="number" min="1" name="product[{{ $product->id }}][qty]" value="{{ $product->qty }}"> {{ $product->units }}
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="{{ route('order.item.remove', $product) }}" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 mt-2 me-2">
                                                <i class="bi bi-trash3"></i>
                                            </a>
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
                </div>
                <div class="col-lg-4">
                    <div class="card shadow shadow">
                        <div class="card-header {{ $order->list_class }} text-white pe-2">
                            <span class="d-inline-block pt-1">
                                <i class="bi bi-info-circle"></i> {{ __($order->status->name) }}
                            </span>
                            <div class="float-end">
                                @role('super_admin')
                                <button type="submit" class="btn btn-sm btn-light d-inline-block text-primary">
                                    <i class="bi bi-check-lg"></i> Save changes
                                </button>
                                @endrole
                            </div>
                        </div>
                        <div class="card-body">
                            <div>
                                @if($order->warehouse()->exists())
                                    <div class="mb-3">
                                        {{ __('Warehouse') }}
                                        <select class="form-select" name="warehouse_id">
                                            @foreach($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}" {{ $order->warehouse->id === $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }} {{ $order->warehouse->id === $warehouse->id ? '*' : '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="mb-3">
                                    {{ __('Payment method') }}
                                    <select class="form-select" name="payment_method_id">
                                        @foreach($paymentMethods as $paymentMethod)
                                            <option value="{{ $paymentMethod->id }}" {{ $order->paymentMethod->id === $paymentMethod->id ? 'selected' : '' }}>{{ $paymentMethod->name }} {{ $order->paymentMethod->id === $paymentMethod->id ? '*' : '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    {{ __('Created') }}: <b>{{ $order->created_at }}</b>
                                </div>
                                <div>
                                    {{ __('Updated') }}: <b>{{ $order->updated_at }}</b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        let searchInput = document.getElementById('productSearch');
        let searchSuggestions = document.getElementById('searchSuggestions');

        async function postData(url = '', data = {}) {
            const response = await fetch(url, {
                method: 'POST',
                mode: 'cors',
                cache: 'no-cache',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json'
                },
                redirect: 'follow',
                referrerPolicy: 'no-referrer',
                body: JSON.stringify(data)
            });
            return response.json();
        }

        function createResultElement(item) {
            var element = document.createElement('a');
            element.classList.add('d-block', 'text-decoration-none', 'text-black');
            element.href = '{{ route('order.item.add', ['%order-id%', '%product-id%']) }}'
                .replace('%order-id%', {{ $order->id }})
                .replace('%product-id%', item.id);
            element.innerHTML = document.getElementById('searchSuggestion').innerHTML;
            element.innerHTML = element.innerHTML.replace('%item-name%', item.name)
                .replace('#%item-image%', item.image)
                .replace('%item-id%', item.id)
                .replace('%item-price%', item.price);

            return element;
        }

        function updateSuggestionList() {
            postData('{{ route('api.products.search') }}', { criteria: document.getElementById('productSearch').value })
                .then(data => {
                    if(document.getElementById('productSearch').value === '') {
                        searchSuggestions.classList.add('d-none');
                    } else {
                        searchSuggestions.classList.remove('d-none');

                        if(data.length < 1) {
                            searchSuggestions.innerHTML = '{{ __('No products') }}';
                        } else {
                            searchSuggestions.innerHTML = '';
                            data.forEach((item) => {
                                searchSuggestions.append(createResultElement(item));
                            });
                        }
                    }
                });
        }

        searchInput.addEventListener('input', function () {
            updateSuggestionList();
        });
    </script>

    <script id="searchSuggestion" type="text/html">
        <div class="p-2" style="border-bottom: 1px solid #ddd;">
            <div class="d-inline-block text-center" style="width: 50px">
                <img src="#%item-image%" class="card-img-top w-auto" style="height: 30px;">
            </div>
            %item-name%
            <span class="badge bg-secondary bg-opacity-10 text-secondary">%item-price%</span>
            <span class="badge bg-primary bg-opacity-10 text-secondary">ID: %item-id%</span>
        </div>
    </script>
@endsection
