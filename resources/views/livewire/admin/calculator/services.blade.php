<div>
    <div class="modal-header">
        <h5 class="modal-title">{{ __('Services') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row mb-3">
            <div class="col-4">
                <div class="card">
                    <div class="card-body p-3">
                        <h5>{{ __('Select pricer') }}</h5>
                        <div class="input-group input-group-sm">
                            <label class="input-group-text">{{ __('Pricer') }}</label>
                            <select class="form-select" wire:model="selectedPricePeriod">
                                <option value="" disabled selected>{{ __('Select pricer') }}</option>
                                @if($pricePeriods)
                                    @foreach($pricePeriods as $pricePeriod)
                                        <option value="{{ $pricePeriod->id }}">{{ $pricePeriod->name }} ({{ $pricePeriod->from }} - {{ $pricePeriod->to }})</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-body p-3">
                        <h5>{{ __('Create pricer') }}</h5>
                        <div class="input-group input-group-sm">
                            <label class="input-group-text">{{ __('Name') }}</label>
                            <input type="text" class="form-control" wire:model="selected.period_name">
                            <label class="input-group-text">{{ __('From') }}</label>
                            <input type="date" class="form-control" wire:model="selected.date_from">
                            <label class="input-group-text">{{ __('To') }}</label>
                            <input type="date" class="form-control" wire:model="selected.date_to">
                            <button class="btn btn-sm btn-primary" wire:click="createPeriod">{{ __('Create') }}</button>
                        </div>
                    </div>
                </div>
                <div>@error('selected.period_name') <span class="text-danger">{{ $message }}</span> @enderror</div>
                <div>@error('selected.date_from') <span class="text-danger">{{ $message }}</span> @enderror</div>
                <div>@error('selected.date_to') <span class="text-danger">{{ $message }}</span> @enderror</div>
            </div>
        </div>
        @if(!$pricePeriods || $pricePeriods->count() === 0)
            <div class="alert alert-warning mt-3 mb-0">
                {{ __('You must create at least one price period before creating services.') }}
            </div>
        @else
            @if($selectedPricePeriod !== '')
                <table class="table table-bordered mb-0">
                    <thead>
                    <tr class="bg-secondary text-white">
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Units') }}</th>
                        <th scope="col">{{ __('Step') }}</th>
                        <th scope="col">{{ __('Price') }}</th>
                        <th scope="col">{{ __('Min price') }}</th>
                        <th scope="col">{{ __('Mid price') }}</th>
                        <th scope="col">{{ __('Max price') }}</th>
                        <th scope="col" style="width:160px">{{ __('Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($services as $service)
                        <tr>
                            <td>
                                @if($edit == $service->id)
                                    <input type="text" class="form-control form-control-sm" wire:model="selected.edit_name">
                                    @error('selected.edit_name') <span class="text-danger">{{ $message }}</span> @enderror
                                @else
                                    {{ $service->name }}
                                @endif
                            </td>
                            <td>
                                @if($edit == $service->id)
                                    <input type="text" class="form-control form-control-sm" wire:model="selected.edit_units">
                                    @error('selected.edit_units') <span class="text-danger">{{ $message }}</span> @enderror
                                @else
                                    {{ $service->units }}
                                @endif
                            </td>
                            <td>
                                @if($edit == $service->id)
                                    <input type="number" class="form-control form-control-sm" wire:model="selected.edit_step">
                                    @error('selected.edit_step') <span class="text-danger">{{ $message }}</span> @enderror
                                @else
                                    {{ $service->step }}
                                @endif
                            </td>
                            <td>
                                @if($edit == $service->id)
                                    <input type="number" class="form-control form-control-sm" wire:model="selected.edit_price">
                                    @error('selected.edit_price') <span class="text-danger">{{ $message }}</span> @enderror
                                @else
                                    {{ $service->price }}
                                @endif
                            </td>
                            <td>
                                @if($edit == $service->id)
                                    <input type="number" class="form-control form-control-sm" wire:model="selected.edit_min_price">
                                    @error('selected.edit_min_price') <span class="text-danger">{{ $message }}</span> @enderror
                                @else
                                    {{ $service->min_price }}
                                @endif
                            </td>
                            <td>
                                @if($edit == $service->id)
                                    <input type="number" class="form-control form-control-sm" wire:model="selected.edit_mid_price">
                                    @error('selected.edit_mid_price') <span class="text-danger">{{ $message }}</span> @enderror
                                @else
                                    {{ $service->mid_price }}
                                @endif
                            </td>
                            <td>
                                @if($edit == $service->id)
                                    <input type="number" class="form-control form-control-sm" wire:model="selected.edit_max_price">
                                    @error('selected.edit_max_price') <span class="text-danger">{{ $message }}</span> @enderror
                                @else
                                    {{ $service->max_price }}
                                @endif
                            </td>
                            <td>
                                @if($edit == $service->id)
                                    <button class="btn btn-primary btn-sm" wire:click="update({{ $service->id }})">{{ __('Save') }}</button>
                                    <button class="btn btn-secondary btn-sm" wire:click="cancelEdit">{{ __('Cancel') }}</button>
                                @else
                                    <button class="btn btn-sm btn-warning" wire:click="edit({{ $service->id }})">{{ __('Edit') }}</button>
                                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $service->id }})">{{ __('Delete') }}</button>
                                @endif
                                @error('delete.'.$service->id) <div class="text-danger">{{ $message }}</div> @enderror
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>
                            <input type="text" class="form-control form-control-sm" wire:model="fields.name" wire:keydown.enter="create">
                            @error('fields.name') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm" wire:model="fields.units" wire:keydown.enter="create">
                            @error('fields.units') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" wire:model="fields.step" wire:keydown.enter="create">
                            @error('fields.step') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" wire:model="fields.price" wire:keydown.enter="create">
                            @error('fields.price') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" wire:model="fields.min_price" wire:keydown.enter="create">
                            @error('fields.min_price') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" wire:model="fields.mid_price" wire:keydown.enter="create">
                            @error('fields.mid_price') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" wire:model="fields.max_price" wire:keydown.enter="create">
                            @error('fields.max_price') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>
                        <td>
                            <button class="btn btn-sm btn-success" wire:click="create">{{ __('Create') }}</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="mt-3">
                    <div class="input-group input-group-sm">
                        <label class="input-group-text bg-warning">{{ __('Copy services and prices from:') }}</label>
                        <select class="form-select" wire:model="selectedPricePeriodToCopy">
                            <option value="" disabled selected>{{ __('Select pricer') }}</option>
                            @if($pricePeriods)
                                @foreach($pricePeriods as $pricePeriod)
                                    @if((int)$pricePeriod->id !== (int)$selectedPricePeriod)
                                        <option value="{{ $pricePeriod->id }}">{{ $pricePeriod->name }} ({{ $pricePeriod->from }} - {{ $pricePeriod->to }})</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                        <button class="btn btn-sm btn-warning" wire:click="copyServices">{{ __('Copy') }}</button>
                    </div>
                </div>
            @else
                <div class="alert alert-secondary mt-3 mb-0">
                    {{ __('Select pricer') }}
                </div>
            @endif
        @endif
    </div>
</div>
