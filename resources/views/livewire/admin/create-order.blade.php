<div>
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __('Create order') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-8">
                @if($success)
                    <div class="alert alert-success">
                        {{ __('Order created successfully') }}
                    </div>
                @endif
                <label class="form-label">{{ __('Products') }}</label>
                <div class="position-relative mb-2">
                    <input wire:model.debounce.200ms="searchQuery" placeholder="{{ __('Add product') }}" class="form-control shadow-none {{ !$searchQuery ?: 'rounded-0 rounded-top' }}">
                    <div class="form-control position-absolute start-0 end-0 {{ !$searchQuery ? 'd-none' : 'rounded-0 rounded-bottom border-top-0 shadow' }}">

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
                            <td><input type="number" class="form-control form-control-sm w-100" wire:model="productQty.{{ $product->id }}"></td>
                            <td>
                                <button wire:click="remove({{ $product->id }})" class="btn btn-sm btn-danger">{{ __('Remove') }}</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                {{ __('No products') }}
                                @error('products')
                                <div class="small text-danger">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>
                    @endforelse
                    <tr class="border-0">
                        <td colspan="4" class="text-end border-0">{{ __('Subtotal') }}</td>
                        <td class="border">{{ price_format($subTotal) }}€</td>
                    </tr>
                    <tr class="border-0">
                        <td colspan="4" class="text-end border-0">
                            <div class="form-check form-switch d-inline-block">
                                <input wire:model="addPvm" value="1" class="form-check-input" type="checkbox" role="switch" id="add_pvm">
                                <label class="form-check-label" for="add_pvm">{{ __('Add PVM') }}</label>
                            </div>
                        </td>
                        <td class="border">{{ $addPvm ? setting('pvm') : '0' }}%</td>
                    </tr>
                    <tr class="border-0">
                        <td colspan="4" class="text-end border-0">{{ __('Total') }}</td>
                        <td class="border">{{ price_format($total) }}€</td>
                    </tr>
                    </tbody>
                </table>
                <div class="row">
                    @if($orderType === config('constants.ORDER_TYPE_NORMAL'))
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input wire:model="preInvoiceRequired" value="1" class="form-check-input" type="checkbox" role="switch" id="pre_invoice_required">
                                <label class="form-check-label" for="pre_invoice_required">{{ __('Pre-invoice required') }}</label>
                            </div>
                        </div>
                        @if ($preInvoiceRequired)
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Full name') }}</label>
                                    <input type="text" id="name" wire:model="name" name="name" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="company_name" class="form-label">{{ __('Company name') }}</label>
                                    <input type="text" id="company_name" wire:model="companyName" name="company_name" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="company_code" class="form-label">{{ __('Registration code') }}</label>
                                    <input type="text" id="company_code" wire:model="companyCode" name="company_code" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="company_phone" class="form-label">{{ __('Phone number') }}</label>
                                    <input type="text" id="company_phone" wire:model="companyPhone" name="company_phone" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email') }}</label>
                                    <input type="text" id="email" wire:model="email" name="email" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">{{ __('Address') }}</label>
                                    <input type="text" id="address" wire:model="address" name="address" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="pvm_code" class="form-label">{{ __('VAT number') }}</label>
                                    <input type="text" id="pvm_code" wire:model="pvmCode" name="pvm_code" class="form-control">
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="order_type" class="form-label">{{ __('Type') }}</label>
                    <select class="form-select" id="order_type" wire:model="orderType">
                        @foreach($orderTypes as $type => $name)
                            <option value="{{ $type }}">{{ __($name) }}</option>
                        @endforeach
                    </select>
                </div>
                @if($orderType === config('constants.ORDER_TYPE_ISSUE'))
                    <div class="mb-3">
                        <div class="form-check form-switch d-inline-block">
                            <input wire:model="waybillRequired" value="1" class="form-check-input" type="checkbox" role="switch" id="waybill_required">
                            <label class="form-check-label" for="waybill_required">{{ __('Bill of lading is required') }}</label>
                        </div>
                    </div>
                @endif
                <div class="mb-3">
                    <label for="customer" class="form-label">{{ __('Customer') }}</label>
                    <select class="form-select" id="customer" wire:model="selectedCustomer">
                        <option hidden>{{ __('Select') }}</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedCustomer')
                    <div class="small text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="warehouse" class="form-label">{{ __('Warehouse') }}</label>
                    <select class="form-select" id="warehouse" wire:model="selectedWarehouse">
                        <option hidden>{{ __('Select') }}</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedWarehouse')
                    <div class="small text-danger">{{ $message }}</div>
                    @enderror
                </div>
                @if($orderType === config('constants.ORDER_TYPE_NORMAL'))
                    <div class="mb-3">
                        <label for="paymentmethod" class="form-label">{{ __('Payment method') }}</label>
                        <select class="form-select" id="paymentmethod" wire:model="selectedPaymentMethod">
                            <option hidden>{{ __('Select') }}</option>
                            @foreach($paymentMethods as $payment_method)
                                <option value="{{ $payment_method->id }}">{{ __($payment_method->name) }}</option>
                            @endforeach
                        </select>
                        @error('selectedPaymentMethod')
                        <div class="small text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
                <div class="mb-3">
                    <label for="message" class="form-label">{{ __('Message') }}</label>
                    <textarea name="message" wire:model="message" id="message" class="form-control"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button wire:click.prevent="submit" class="btn btn-primary">{{ __('Create') }}</button>
    </div>
</div>
