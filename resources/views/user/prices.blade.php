@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <a href="{{ route('user.show', $user) }}" class="btn btn-outline-primary">{{ __('User info') }}</a>
                            <a href="{{ route('user.orders', $user) }}" class="btn btn-outline-primary">{{ __('Orders') }}</a>
                            <a href="{{ route('user.items', $user) }}" class="btn btn-outline-primary">{{ __('Ordered items') }}</a>
                            <a href="{{ route('user.prices', $user) }}" class="btn btn-primary">{{ __('User prices') }}</a>
                        </div>
                        <h2 class="d-inline-block">{{ $user->name }} {{ __('Prices') }}</h2>
                        <div>
                            {{ __('Select product and add price to user') }}:
                        </div>
                        <div class="mb-3 mt-1">
                            <div class="position-relative">
                                <div class="input-group rounded">
                                    <input type="text" class="form-control rounded" autocomplete="off" placeholder="{{ __('Search') }}" name="query" id="productSearch" />
                                </div>
                                <div class="position-absolute start-0 end-0 bg-light p-2 mt-1 rounded border border-white shadow d-none zindex-10" id="searchSuggestions"></div>
                            </div>
                        </div>
                        <table class="table mt-3">
                            <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">{{ __('Product name') }}</th>
                                <th scope="col">{{ __('Price') }}</th>
                                <th scope="col">{{ __('User price') }}</th>
                                <th scope="col">{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($user->prices as $product)
                                <tr>
                                    <th scope="row">{{ $product->id }}</th>
                                    <th scope="row">{{ $product->name }}</th>
                                    <td>{{ $product->original_price }}€</td>
                                    <td>{{ $product->pivot->price }}€</td>
                                    <td>
                                        <a href="{{ route('user.prices.set', [$user, $product]) }}" class="btn btn-warning btn-sm d-inline-block">{{ __('Edit') }}</a>
                                        <form method="post" action="{{ route('user.prices.delete', [$user, $product]) }}" class="d-inline-block" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-sm d-inline-block">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">{{ __('Empty') }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
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
            element.href = '{{ route('user.prices.set', ['%user-id%', '%product-id%']) }}'
                .replace('%user-id%', {{ $user->id }})
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
