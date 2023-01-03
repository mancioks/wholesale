@extends('layouts.front')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="col">
                @livewire('product-search-component')
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="bg-white p-3 mb-3 shadow-sm">
                    <h2 class="fs-5">{{ __('Warehouse') }}</h2>
                    <p class="mb-2 mt-n2 text-secondary small">
                        {{ __('If warehouse is changed, items in the cart will be removed') }}
                    </p>
                    @foreach($warehouses as $warehouse)
                        <div class="form-check" data-bs-toggle="tooltip" data-bs-placement="left" aria-label="{{ $warehouse->address }}" data-bs-original-title="{{ $warehouse->address }}">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="order-warehouse-{{ $warehouse->id }}"
                            onclick="document.location.href='{{ route('warehouse.set', $warehouse) }}'"
                            @if(auth()->user()->warehouse->id === $warehouse->id) checked @endif>
                            <label class="form-check-label" for="order-warehouse-{{ $warehouse->id }}">
                                {{ $warehouse->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="bg-white p-3 shadow-sm">
                    <form action="" method="get">
                        <h2 class="fs-5">{{ __('Category') }}</h2>
                        @foreach($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="category[{{ $category->id }}]" value="1" id="category-filter-{{ $category->id }}">
                                <label class="form-check-label" for="category-filter-{{ $category->id }}">
                                    {{ $category->name }}
                                </label>
                            </div>
                        @endforeach
                        <button type="submit" class="btn btn-primary mt-3 w-100">{{ __('Filterate') }}</button>
                    </form>
                </div>
            </div>
            <div class="col-9">
                <div class="row gx-2 gy-2">
                    @forelse($products as $product)
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="card border-0 rounded-0 h-100 product-card">
                                <div class="card-body p-0 product-card-body-wrapper">
                                    <div class="bg-white p-2 text-center mb-3 product-img-wrapper">
                                        <img src="{{ asset($product->image->name) }}">
                                    </div>
                                    <h3 class="card-title p-2 fs-6">{{ $product->name }}</h3>
                                    <div class="product-price-wrapper">
                                        <p class="card-text mb-2">
                                            <strong>
                                                {{ explode('.', $product->price)[0] }}
                                                <sup>{{ explode('.', $product->price)[1] }}</sup>
                                            </strong>
                                            € / {{ $product->units }}
                                        </p>
                                        <div>
                                            <form action="{{ route('cart.add', $product->id) }}" method="post">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-12">
                                                        <input type="hidden" name="qty" value="1" class="form-control text-center remove-outline border-1 border-dark cursor-auto w-100">
                                                        <button type="submit" class="btn btn-sm btn-dark w-100 text-center">{{ __('Add to cart') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div>{{ __('No products found in selected warehouse') }}</div>
                    @endforelse
                </div>
                <div>
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
