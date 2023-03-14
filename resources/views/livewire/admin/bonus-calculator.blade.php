<div>
    <div class="row mb-3 gx-2 gy-2">
        <div class="col-2">
            <div class="card shadow shadow-sm">
                <div class="card-body pb-2 pt-2">
                    <h5 class="card-title">{{ __('Estimate total') }}</h5>
                    <p class="card-text">{{ $calculation->estimate_total }} €</p>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="card shadow shadow-sm">
                <div class="card-body pb-2 pt-2">
                    <h5 class="card-title">{{ __('Invoice total') }}</h5>
                    <p class="card-text">{{ $calculation->invoice_total }} €</p>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="card shadow shadow-sm">
                <div class="card-body pb-2 pt-2">
                    <h5 class="card-title">{{ __('Fact MIN') }}</h5>
                    <p class="card-text">{{ $fact['min'] }} €</p>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="card shadow shadow-sm">
                <div class="card-body pb-2 pt-2">
                    <h5 class="card-title">{{ __('Fact MID') }}</h5>
                    <p class="card-text">{{ $fact['mid'] }} €</p>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="card shadow shadow-sm">
                <div class="card-body pb-2 pt-2">
                    <h5 class="card-title">{{ __('Fact MAX') }}</h5>
                    <p class="card-text">{{ $fact['max'] }} €</p>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="card shadow shadow-sm">
                <div class="card-body pb-2 pt-2">
                    <h5 class="card-title">{{ __('Invoice') }} - {{ __('Fact') }}</h5>
                    <p class="card-text">{{ $difference['invoice'] }} €</p>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="card shadow shadow-sm">
                <div class="card-body pb-2 pt-2">
                    <h5 class="card-title">{{ __('Estimate') }} - {{ __('Fact') }}</h5>
                    <p class="card-text">{{ $difference['estimate'] }} €</p>
                </div>
            </div>
        </div>
    </div>
    <table class="table table-bordered mb-0">
        <thead>
        <tr class="bg-secondary text-white">
            <th scope="col">{{ __('Service') }}</th>
            <th scope="col">{{ __('Price') }}</th>
            <th scope="col">{{ __('Quantity') }}</th>
            <th scope="col">{{ __('Total') }}</th>
            <th scope="col">{{ __('Actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @if($calculation->estimateData)
            @foreach($calculation->estimateData as $estimateData)
                <tr>
                    <td>{{ $estimateData->service->name }}</td>
                    <td>
                        {{ $estimateData->service->price }} € / {{ $estimateData->service->step }} {{ $estimateData->service->units }}
                    </td>
                    <td>
                        @if($edit == $estimateData->id)
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" wire:model="selected.edit_qty" min="0" step=".01">
                                <span class="input-group-text">{{ $estimateData->service->units }}</span>
                            </div>
                            @error('selected.edit_qty') <span class="text-danger">{{ $message }}</span> @enderror
                        @else
                            {{ $estimateData->qty }} {{ $estimateData->service->units }}
                        @endif
                    </td>
                    <td>
                        @if($edit == $estimateData->id)
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" wire:model="selected.edit_total" min="0" step=".01">
                                <span class="input-group-text">€</span>
                            </div>
                            @error('selected.edit_total') <span class="text-danger">{{ $message }}</span> @enderror
                        @else
                            {{ $estimateData->actual_amount }} €
                        @endif
                    </td>
                    <td>
                        @if($edit == $estimateData->id)
                            <button class="btn btn-primary btn-sm" wire:click="update({{ $estimateData->id }})">{{ __('Save') }}</button>
                            <button class="btn btn-secondary btn-sm" wire:click="cancelEdit">{{ __('Cancel') }}</button>
                        @else
                            <button class="btn btn-warning btn-sm" wire:click="edit({{ $estimateData->id }})">{{ __('Edit') }}</button>
                            <button class="btn btn-danger btn-sm" wire:click="delete({{ $estimateData->id }})">{{ __('Delete') }}</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        <tr>
            <td>
                <select class="form-select form-select-sm" wire:model="selected.service">
                    <option value=""></option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>
                @error('selected.service') <span class="text-danger">{{ $message }}</span> @enderror
            </td>
            <td>{{ $selected['price'] }}</td>
            <td>
                <div class="input-group input-group-sm">
                    <input type="number" class="form-control" wire:model="selected.qty" min="0" step=".01">
                    <span class="input-group-text">{{ $selected['units'] }}</span>
                </div>
                @error('selected.qty') <span class="text-danger">{{ $message }}</span> @enderror
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <input type="number" class="form-control" wire:model="selected.total" min="0" step=".01">
                    <span class="input-group-text">€</span>
                </div>
                @error('selected.total') <span class="text-danger">{{ $message }}</span> @enderror
            </td>
            <td>
                <button class="btn btn-success btn-sm" wire:click="add">{{ __('Add') }}</button>
            </td>
        </tr>
        </tbody>
    </table>
</div>
