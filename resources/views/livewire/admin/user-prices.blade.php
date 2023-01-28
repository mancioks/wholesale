<div>
    @if($success)
        <div class="alert alert-success" role="alert">
            {{ __('Saved') }}
        </div>
    @endif
    @include('components.livewire.product_search')
    <table class="table table-bordered mt-3 mb-0">
        <thead>
        <tr class="bg-secondary text-white">
            <th scope="col">#</th>
            <th scope="col">{{ __('Product name') }}</th>
            <th scope="col">{{ __('Price') }}</th>
            <th scope="col">{{ __('User price') }}</th>
            <th scope="col">{{ __('Actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($products as $id => $product)
            <tr>
                <th scope="row" style="width: 80px">{{ $product['code'] }}</th>
                <td>{{ $product['name'] }}</td>
                <td>{{ $productsOriginalPrices[$id] }}€</td>
                <td>
                    <input type="number" class="form-control form-control-sm" min="0" id="productsPrices.{{ $id }}" wire:model="productsPrices.{{ $id }}">
                </td>
                <td>
                    <button class="btn btn-danger btn-sm" wire:click="removeProduct({{ $id }})">{{ __('Remove') }}</button>
                </td>
            </tr>
            @error('productsPrices.' . $id)
            <tr>
                <td colspan="5">
                    <small class="text-danger text-center d-block">{{ $message }}</small>
                </td>
            </tr>
            @enderror
        @empty
            <tr>
                <td colspan="5">{{ __('Empty') }}</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    @if($changed)
        <button class="btn btn-sm btn-primary mt-3" wire:click="save">{{ __('Save') }}</button>
    @endif
</div>
