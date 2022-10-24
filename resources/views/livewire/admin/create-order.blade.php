<div>
    <div><input wire:model.debounce.100ms="searchQuery"></div>
    <div>
        @forelse($searchResults as $searchResult)
            <div>{{ $searchResult->name }} <button wire:click="add({{$searchResult->id}})">Add</button></div>
        @empty
            @if ($searchQuery && $searchQuery !== '')
                <div>No results</div>
            @endif
        @endforelse
    </div>
    @forelse($products as $product)
        <div>{{ $product->name }} <input type="number" wire:model.defer="productQty.{{ $product->id }}"></div>
    @empty
        No products
    @endforelse
</div>
