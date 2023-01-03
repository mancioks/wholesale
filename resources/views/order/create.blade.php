@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body pb-0">
                        <div class="row">
                            <h2>{{ __('New order') }}</h2>
                        </div>
                        <div class="mb-3">
                            <form action="{{ route('order.create') }}" method="get">
                                <div class="position-relative">
                                    <div class="input-group rounded">
                                        <input type="text" class="form-control rounded" autocomplete="off" placeholder="{{ __('Search') }}" name="query" id="productSearch" value="{{ $search_query }}" />
                                        <button type="submit" class="input-group-text border-0">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div class="position-absolute start-0 end-0 bg-light p-2 mt-1 rounded border border-white shadow d-none zindex-10" id="searchSuggestions">
                                        <div class="">August AT-8</div>
                                        <div class="">August AT-8</div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="warehouse-method mt-3 mb-3">
                            <h4>{{ __('Warehouse') }}</h4>
                            @foreach($warehouses as $warehouse)
                                <a href="{{ route('warehouse.set', $warehouse) }}" class="btn btn-outline-dark text-start mb-1 @if(auth()->user()->warehouse->id === $warehouse->id) bg-dark text-white @endif ">
                                    <b>{{ $warehouse->name }}</b><br>
                                    {{ $warehouse->address }}
                                </a>
                            @endforeach
                        </div>
                        <div>
                            {{ $products->links('pagination::bootstrap-5') }}
                        </div>
                        @if($search_query)
                            <div class="mb-3">
                                <div class="h4 mb-0">
                                    Search results for: "{{ $search_query }}"
                                </div>
                                <div>
                                    <a class="small text-decoration-none text-primary" href="{{ route('order.create') }}">Show all</a>
                                </div>
                            </div>
                        @endif
                        <div class="products-wrapper row gx-2 gy-2">
                            @forelse($products as $product)
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="card shadow-sm">
                                        <div class="card-body p-2">
                                            <div class="bg-light p-2 text-center mb-3 rounded product-img-wrapper">
                                                <img src="{{ asset($product->image->name) }}">
                                            </div>
                                            <h5 class="card-title">{{ $product->name }}</h5>
                                            <p class="card-text mb-2">
                                                {{ $product->price }}€ / {{ $product->units }}
                                                @if($product->pivot && !$product->pivot->enabled)
                                                    <span class="badge bg-danger ms-1">{{ __('Not for sale') }}</span>
                                                @endif
                                            </p>
                                            <form action="{{ route('cart.add', $product->id) }}" method="post">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-4 pe-0">
                                                        <input type="number" name="qty" value="1" class="form-control text-center remove-outline border-1 border-dark cursor-auto w-100">
                                                    </div>
                                                    <div class="col-8 ps-1">
                                                        <button type="submit" class="btn btn-dark w-100 text-center">{{ __('Add to cart') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div>{{ __('No products found in selected warehouse') }}</div>
                            @endforelse
                        </div>
                        <div class="mt-4 mb-1">
                            {{ $products->links('pagination::bootstrap-5') }}
                        </div>
                        @if($userPopularProducts)
                            <div class="products-wrapper row gx-2 gy-2 mb-3">
                                <h2>{{ __('Recommended products') }}</h2>
                                @foreach($userPopularProducts as $userPopularProduct)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="card shadow-sm">
                                            <div class="card-body p-2">
                                                <div class="bg-light p-2 text-center mb-3 rounded product-img-wrapper">
                                                    <img src="{{ asset($userPopularProduct->image->name) }}">
                                                </div>
                                                <h5 class="card-title">{{ $userPopularProduct->name }}</h5>
                                                <p class="card-text mb-2">{{ $userPopularProduct->price }}€ / {{ $userPopularProduct->units }}</p>
                                                <form action="{{ route('cart.add', $userPopularProduct->id) }}" method="post">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-4 pe-0">
                                                            <input type="number" name="qty" value="1" class="form-control text-center remove-outline border-1 border-dark cursor-auto w-100">
                                                        </div>
                                                        <div class="col-8 ps-1">
                                                            <button type="submit" class="btn btn-dark w-100 text-center">{{ __('Add to cart') }}</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
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
            element.href = '{{ route('order.create') }}?query=%search-query%'.replace('%search-query%', item.name);
            element.innerHTML = document.getElementById('searchSuggestion').innerHTML;
            element.innerHTML = element.innerHTML.replace('%item-name%', item.name)
                .replace('#%item-image%', item.image);

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
        </div>
    </script>
@endsection
