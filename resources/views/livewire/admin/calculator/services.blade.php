<div>
    <div class="modal-header">
        <h5 class="modal-title">{{ __('Services') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <table class="table table-bordered mb-0">
            <thead>
            <tr class="bg-secondary text-white">
                <th scope="col">#</th>
                <th scope="col">{{ __('Name') }}</th>
                <th scope="col">{{ __('Units') }}</th>
                <th scope="col">{{ __('Step') }}</th>
                <th scope="col">{{ __('Price') }}</th>
                <th scope="col">{{ __('Min price') }}</th>
                <th scope="col">{{ __('Mid price') }}</th>
                <th scope="col">{{ __('Max price') }}</th>
                <th scope="col">{{ __('Action') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($services as $service)
                <tr>
                    <td>{{ $service->id }}</td>
                    <td>{{ $service->name }}</td>
                    <td>{{ $service->units }}</td>
                    <td>{{ $service->step }}</td>
                    <td>{{ $service->price }}</td>
                    <td>{{ $service->min_price }}</td>
                    <td>{{ $service->mid_price }}</td>
                    <td>{{ $service->max_price }}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" wire:click="delete({{ $service->id }})">{{ __('Delete') }}</button>
                        @error('delete.'.$service->id) <div class="text-danger">{{ $message }}</div> @enderror
                    </td>
                </tr>
            @endforeach
            <tr>
                <td></td>
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
    </div>
</div>
