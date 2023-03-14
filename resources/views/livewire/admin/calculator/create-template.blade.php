<div>
    <div class="modal-header">
        <h5 class="modal-title">{{ __('Create template') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <label for="name" class="form-label">{{ __('Template name') }}</label>
        <input type="text" wire:model="name" id="name" class="form-control">
        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button class="btn btn-primary" wire:click="create">{{ __('Create') }}</button>
    </div>
</div>
