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
                            <form action="{{ route('order.create') }}" method="get" class="input-group rounded">
                                <input type="text" class="form-control rounded" placeholder="{{ __('Search') }}" name="query" value="{{ $search_query }}" />
                                <button type="submit" class="input-group-text border-0">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
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
                                            <p class="card-text mb-2">{{ $product->price }}€ / {{ $product->units }}</p>
                                            <form action="{{ route('cart.add', $product->id) }}" method="post">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-4">
                                                        <input type="number" name="qty" value="1" class="btn btn-light text-center remove-outline border-1 border-dark cursor-auto w-100">
                                                    </div>
                                                    <div class="col-8">
                                                        <button type="submit" class="btn btn-dark w-100 text-center">{{ __('Add to cart') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div>{{ __('No products found') }}</div>
                            @endforelse
                        </div>
                        <div class="mt-4 mb-1">
                            {{ $products->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
