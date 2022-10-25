<div>
    <div class="row">
        <div class="col-md-8">
            <label class="form-label">{{ __('Products') }}</label>
            <div class="position-relative mb-2">
                <input wire:model.debounce.200ms="searchQuery" placeholder="{{ __('Add product') }}" class="form-control shadow-none {{ !$searchQuery ?: 'rounded-0 rounded-top' }}">
                <div class="form-control position-absolute start-0 end-0 {{ !$searchQuery ? 'd-none' : 'rounded-0 rounded-bottom border-top-0' }}">

                    @if ($searchResults->isNotEmpty())
                        <div class="products-wrapper row gx-2 gy-2 pt-1 pb-1">
                            @foreach($searchResults as $searchResult)
                                <div class="col-lg-3 col-md-3 col-sm-4 col-6" wire:click="add({{ $searchResult->id }})" role="button">
                                    <div class="card shadow-sm">
                                        <div class="card-body p-2">
                                            <div class="bg-light p-2 text-center mb-2 rounded">
                                                <img src="{{ asset($searchResult->image->name) }}" style="max-height: 50px">
                                            </div>
                                            <h5 class="card-title fs-6 mb-0">{{ $searchResult->name }}</h5>
                                            <p class="card-text mb-n1 fs-6">
                                                {{ $searchResult->original_price }}€ / {{ $searchResult->units }}
                                                @if($searchResult->pivot && !$searchResult->pivot->enabled)
                                                    <span class="badge bg-danger ms-1">{{ __('Not for sale') }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        @if ($searchQuery && $searchQuery !== '')
                            <div>No results</div>
                        @endif
                    @endif

                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                <tr class="bg-secondary text-white">
                    <th>#</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Quantity') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            {{ $product->name }}
                        </td>
                        <td>{{ $product->original_price }}€</td>
                        <td><input type="number" class="form-control form-control-sm w-100" wire:model.defer="productQty.{{ $product->id }}"></td>
                        <td>
                            <button wire:click="remove({{ $product->id }})" class="btn btn-sm btn-danger">{{ __('Remove') }}</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">{{ __('No products') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label for="customer" class="form-label">{{ __('Customer') }}</label>
                <select class="form-select" id="customer" wire:model="selectedCustomer">
                    <option hidden>{{ __('Select') }}</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                {{ $selectedWarehouse }}
                <label for="warehouse" class="form-label">{{ __('Warehouse') }}</label>
                <select class="form-select" id="warehouse" wire:model="selectedWarehouse">
                    <option hidden>{{ __('Select') }}</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="paymentmethod" class="form-label">{{ __('Payment method') }}</label>
                <select class="form-select" id="paymentmethod" wire:model="selectedPaymentMethod">
                    <option hidden>{{ __('Select') }}</option>
                    @foreach($paymentMethods as $payment_method)
                        <option value="{{ $payment_method->id }}">{{ __($payment_method->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
