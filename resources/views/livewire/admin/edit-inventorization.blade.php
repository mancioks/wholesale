<div>
    @include('components.livewire.product_search')
    <table class="table table-bordered mt-3 mb-0">
        <thead>
        <tr class="bg-secondary text-white">
            <th scope="col">{{ __('name') }}</th>
            <th scope="col">{{ __('code') }}</th>
            <th scope="col">{{ __('balance') }}</th>
            <th scope="col">{{ __('units') }}</th>
            <th scope="col">{{ __('Actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($inventorization->items as $index => $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->code }}</td>
                <td>
                    <input
                        type="number"
                        step="1"
                        min="0"
                        wire:model.defer="inventorization.items.{{ $index }}.balance"
                        wire:change="saveItem({{ $item->id }}, $event.target.value)"
                        class="form-control form-control-sm"
                    >
                </td>
                <td>{{ $item->units }}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger d-inline-block" wire:click="deleteItem({{ $item->id }})">
                        <i class="bi bi-trash3-fill"></i> {{ __('Delete') }}
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">{{ __('Empty') }}</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
