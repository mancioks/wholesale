<div>
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __('Create product') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                @if($success)
                    <div class="alert alert-success">
                        {{ __('Product created successfully') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="product_name" class="form-label">{{ __('Product name') }}</label>
                            <input type="text" class="form-control" id="product_name" wire:model="name" name="name" required>
                            @error('name') <div class="small text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="product_code" class="form-label">{{ __('Product code') }}</label>
                            <input type="text" class="form-control" id="product_code" wire:model="code" name="code" required>
                            @error('code') <div class="small text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="product_price" class="form-label">{{ __('Prime cost') }} ({{ __('Without VAT') }})</label>
                            <input type="number" class="form-control" id="product_price" wire:model="price" name="price" step=".01" required>
                            @error('price') <div class="small text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="product_prime_cost" class="form-label">{{ __('Prime cost (old)') }}</label>
                            <input type="number" class="form-control" id="product_prime_cost" wire:model="primeCost" name="prime_cost" step=".01" disabled>
                            @error('primeCost') <div class="small text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="product_units" class="form-label">{{ __('Units') }}</label>
                            <input type="text" class="form-control" id="product_units" wire:model="units" name="units" required>
                            @error('units') <div class="small text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Photo') }}</label>
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="product_type" class="form-label">{{ __('Type') }}</label>
                    <select class="form-select" id="product_type" wire:model="productType">
                        @foreach($productTypes as $type => $name)
                            <option value="{{ $type }}">{{ __($name) }}</option>
                        @endforeach
                    </select>
                </div>
                @if($productType === config('constants.PRODUCT_TYPE_PERSONALIZED'))
                    <div class="mb-3">
                        <label for="customer" class="form-label">{{ __('Customers') }}</label>
                        <div class="input-group">
                            <select class="form-select" id="customer" wire:model="selectedCustomer">
                                <option hidden>{{ __('Select') }}</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary" wire:click="addCustomer">{{ __('Add') }}</button>
                        </div>
                        @error('selectedCustomer') <div class="small text-danger">{{ $message }}</div> @enderror
                        @if($productUsers->isNotEmpty())
                            <div>
                                {{ __('Selected:') }}
                                @foreach($productUsers as $productUser)
                                    <div class="badge bg-light text-secondary border mt-1">
                                        {{ $productUser->name }}
                                        <span class="d-inline-block bg-danger text-white px-1 py-1 rounded ms-1" role="button" wire:click="removeCustomer({{ $productUser->id }})">✖</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @error('productUsers') <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>
                @endif
                <table class="table table-bordered">
                    <thead>
                    <tr class="bg-secondary text-white">
                        <th scope="col">{{ __('Warehouse') }}</th>
                        <th scope="col">{{ __('Prime cost') }}</th>
                        <th scope="col">{{ __('Can buy') }}</th>
                    </tr>
                    </thead>
                    <tbody class="border-top-0">
                    @foreach($warehouses as $warehouse)
                        <tr>
                            <th scope="row">{{ $warehouse->name }}</th>
                            <td>
                                <input class="form-control form-control-sm w-50 d-inline-block" type="number" wire:model="warehousePrices.{{ $warehouse->id }}" wire:key="{{ $warehouse->id }}" min="0" step=".01"/>
                                @if($warehousesChanged[$warehouse->id])
                                    <div class="badge bg-danger text-white d-inline-block">{{ __('Fixed') }}</div>
                                @else
                                    <div class="badge bg-primary text-white d-inline-block">{{ __('Inherited') }}</div>
                                @endif
                                @error('warehousePrices.'.$warehouse->id) <div class="small text-danger">{{ $message }}</div> @enderror
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" value="1" wire:model="warehouseEnabled.{{ $warehouse->id }}" wire:key="{{ $warehouse->id }}">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <button class="btn btn-primary btn-sm float-end" wire:click="resetWarehousePrices">{{ __('Reset inheritance') }}</button>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button wire:click.prevent="submit" class="btn btn-primary">{{ __('Create') }}</button>
    </div>
</div>
