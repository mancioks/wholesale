<div class="w-100 position-relative">
    <input type="text" class="w-100 form-control shadow-none {{ !$showProductSearchResults ?: 'rounded-0 rounded-top' }}" wire:model="productSearchInput" placeholder="{{ __('Search product') }}">
    <div class="form-control position-absolute bg-white w-100 start-0 end-0 shadow rounded-0 rounded-bottom border-top-0 {{ $showProductSearchResults ?: 'd-none' }}">
        @if($showProductSearchResults)
            @forelse($productSearchResults as $product)
                <div class="py-1">
                    <div wire:click="selectProductSearchResultBefore({{ $product->id }})" class="text-decoration-none cursor-pointer" role="button">
                        <span class="badge bg-secondary">{{ $product->code }}</span>
                        {{ $product->name }}
                    </div>
                </div>
            @empty
                {{ __('No results') }}
            @endforelse
        @endif
    </div>
</div>
