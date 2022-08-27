@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Products') }}</h2>
                        <div class="actions-wrapper">
                            <a href="{{ route('product.create') }}" class="btn btn-success"><i class="bi bi-plus-square"></i> {{ __('Create product') }}</a>
                            <a href="{{ route('product.import') }}" class="btn btn-warning"><i class="bi bi-filetype-csv"></i>{{ __('Import products') }}</a>
                        </div>
                        <div class="mb-3 mt-3">
                            <form action="{{ route('product.index') }}" method="get">
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
                        <div class="mt-3">
                            {{ $products->links('pagination::bootstrap-5') }}
                        </div>
                        @if($search_query)
                            <div class="mb-3">
                                <div class="h4 mb-0">
                                    Search results for: "{{ $search_query }}"
                                </div>
                                <div>
                                    <a class="small text-decoration-none text-primary" href="{{ route('product.index') }}">Show all</a>
                                </div>
                            </div>
                        @endif
                        <table class="table">
                            <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">{{ __('Title') }}</th>
                                <th scope="col">{{ __('Price') }}</th>
                                @role('super_admin')
                                    <th scope="col">{{ __('Prime cost') }}</th>
                                @endrole
                                <th scope="col">{{ __('Image') }}</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <th scope="row">{{ $product->id }}</th>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->price }}</td>
                                    @role('super_admin')
                                        <td>{{ $product->prime_cost ?: '-' }}</td>
                                    @endrole
                                    <td>
                                        <img src="{{ asset($product->image->name) }}" class="card-img-top w-auto" style="height: 30px;">
                                    </td>
                                    <td>
                                        <a href="{{ route('product.edit', $product->id) }}" class="btn btn-primary btn-sm d-inline-block">{{ __('Edit') }}</a>
                                        <form method="post" action="{{ route('product.destroy', $product->id) }}" class="d-inline-block" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-sm d-inline-block">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">{{ __('No products') }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="mt-1">
                            {{ $products->links('pagination::bootstrap-5') }}
                        </div>
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
            element.href = '{{ route('product.edit', '%element-link%') }}'.replace('%element-link%', item.id);
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
