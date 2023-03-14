<div>
    <div class="modal-header">
        <h5 class="modal-title">{{ __('Installers') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <table class="table table-bordered mb-0">
            <thead>
            <tr class="bg-secondary text-white">
                <th scope="col">#</th>
                <th scope="col">{{ __('Name') }}</th>
                <th scope="col">{{ __('Action') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($installers as $installer)
                <tr>
                    <td>{{ $installer->id }}</td>
                    <td>{{ $installer->name }}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" wire:click="delete({{ $installer->id }})">{{ __('Delete') }}</button>
                        @error('delete.'.$installer->id) <div class="text-danger">{{ $message }}</div> @enderror
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
                    <button class="btn btn-sm btn-success" wire:click="create">{{ __('Create') }}</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
