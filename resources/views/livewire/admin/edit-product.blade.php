<div>
    @section('inner-actions')
        <button type="button" class="btn btn-sm btn-primary" data-click-button="save-button">
            <i class="bi bi-check2-circle"></i> {{ __('Save') }}
        </button>
    @endsection

    @if($success)
        <div class="alert alert-success">
            {{ __('Product updated successfully') }}
        </div>
    @endif

    <div class="row">
        <div class="col-4">
            <label class="form-label">{{ __('Photo') }}</label>
            @if($image)
                <div class="form-control text-center mb-2">
                    <img src="{{ asset($image) }}" class="mw-100" height="200px">
                </div>
                <button class="btn btn-warning btn-sm" wire:click="changeOriginalPhoto">{{ __('Remove') }}</button>
            @else
                <div class="{{ $photo ? 'd-none': '' }}">
                    <input type="file" name="image" id="{{ rand() }}" class="form-control" wire:model="photo" accept=".jpg,.jpeg,.bmp,.png,.gif,.webp" wire:loading.class="d-none">
                    <div wire:loading.block wire:target="photo">
                        <img src="{{ asset(setting('images.path').'loading.gif') }}" width="25px" class="mt-n1"/> {{ __('Uploading...') }}
                    </div>
                    @error('photo') <div class="small text-danger">{{ $message }}</div> @enderror
                </div>
                @if ($photo)
                    <div class="form-control mb-2 text-center">
                        <img src="{{ $photo->temporaryUrl() }}" class="mw-100" height="200px">
                    </div>
                    <button class="btn btn-warning btn-sm" wire:click="changePhoto">{{ __('Remove') }}</button>
                @endif
                <button class="btn btn-primary btn-sm {{ $photo ?: 'mt-2' }}" wire:click="resetOriginalPhoto">{{ __('Restore to original') }}</button>
            @endif
        </div>
        <div class="col-8">
            <div class="mb-3">
                <label for="product-name" class="form-label">{{ __('Product name') }}</label>
                <input type="text" class="form-control" id="product-name" wire:model="product.name">
                @error('product.name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label for="product-description" class="form-label">{{ __('Description') }}</label>
                <textarea type="text" class="form-control" id="product-description" wire:model="product.description"></textarea>
                @error('product.description') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="product-code" class="form-label">{{ __('Code') }}</label>
                    <input type="text" class="form-control" id="product-code" wire:model="product.code">
                    @error('product.code') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-4">
                    <label for="product-price" class="form-label">{{ __('Prime cost') }}</label>
                    <input type="number" class="form-control" id="product-price" wire:model="product.price">
                    @error('product.price') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-4">
                    <label for="product-markup" class="form-label">{{ __('Markup') }} (%)</label>
                    <input type="number" class="form-control" id="product-markup" wire:model="product.markup">
                    @error('product.markup') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="product-additional-fees" class="form-label">{{ __('Additional fees') }}</label>
                    <input type="number" class="form-control" id="product-additional-fees" wire:model="product.additional_fees">
                    @error('product.additional_fees') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-4">
                    <label for="product-units" class="form-label">{{ __('Units') }}</label>
                    <input type="text" class="form-control" id="product-units" wire:model="product.units">
                    @error('product.units') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-4">
                    <label for="product-type" class="form-label">{{ __('Type') }}</label>
                    <select class="form-select" id="product-type" wire:model="product.type">
                        @foreach($productTypes as $productType => $productTypeLabel)
                            <option value="{{ $productType }}">{{ $productTypeLabel }}</option>
                        @endforeach
                    </select>
                    @error('product.type') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                <tr class="bg-secondary text-white">
                    <th scope="col">{{ __('Warehouse') }}</th>
                    <th scope="col">{{ __('Prime cost') }}</th>
                    <th scope="col">{{ __('Markup') }}</th>
                    <th scope="col">{{ __('Price with markup') }}</th>
                    <th scope="col">{{ __('Price with PVM') }}</th>
                    <th scope="col">{{ __('Final price') }}</th>
                    <th scope="col">{{ __('Can buy') }}</th>
                    <th scope="col">{{ __('Inheritance') }}</th>
                </tr>
                </thead>
                <tbody class="border-top-0">
                @foreach($warehouses as $warehouse)
                    <tr>
                        <th scope="row">{{ $warehouse->name }}</th>
                        <td>
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" wire:model="warehousePrices.{{ $warehouse->id }}" wire:key="{{ $warehouse->id }}" min="0" step=".01">
                                <span class="input-group-text">€</span>
                            </div>
                            @error('warehousePrices.'.$warehouse->id) <div class="small text-danger">{{ $message }}</div> @enderror
                        </td>
                        <td>
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" wire:model="warehouseMarkups.{{ $warehouse->id }}" wire:key="{{ $warehouse->id }}">
                                <span class="input-group-text">%</span>
                            </div>
                            @error('warehouseMarkups.'.$warehouse->id) <div class="small text-danger">{{ $message }}</div> @enderror
                        </td>
                        <td>
                            {{ price_with_markup($warehousePrices[$warehouse->id], $warehouseMarkups[$warehouse->id]) }}€
                        </td>
                        <td>
                            {{ price_with_pvm(price_with_markup($warehousePrices[$warehouse->id], $warehouseMarkups[$warehouse->id])) }}€
                        </td>
                        <td>
                            {{ price_format(price_with_pvm(price_with_markup($warehousePrices[$warehouse->id], $warehouseMarkups[$warehouse->id])) + (float)$product->additional_fees) }}€
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" value="1" wire:model="warehouseEnabled.{{ $warehouse->id }}" wire:key="{{ $warehouse->id }}">
                            </div>
                        </td>
                        <td>
                            @if($warehousesChanged[$warehouse->id])
                                <div class="badge bg-danger text-white d-inline-block">{{ __('Fixed') }}</div>
                            @else
                                <div class="badge bg-primary text-white d-inline-block">{{ __('Inherited') }}</div>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <button class="btn btn-primary btn-sm float-end" wire:click="resetWarehousePrices">{{ __('Reset inheritance') }}</button>
        </div>
    </div>
    <button type="button" id="save-button" class="btn btn-primary visually-hidden" wire:click="save">{{ __('Save') }}</button>
</div>
