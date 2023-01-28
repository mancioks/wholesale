<div>
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __('Edit user') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-12">
                @if($success)
                    <div class="alert alert-success">
                        {{ __('User updated successfully') }}
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="mb-3">
                    <label for="user_name" class="form-label">{{ __('Full name') }}</label>
                    <input type="text" class="form-control" wire:model="user.name">
                </div>
                <div class="mb-3">
                    <label for="user_email" class="form-label">{{ __('Email') }}</label>
                    <input type="email" class="form-control" id="user_email" name="email" wire:model="user.email">
                </div>
                @role('super_admin')
                <div class="mb-3">
                    <label for="user_role" class="form-label">{{ __('Role') }}</label>
                    <select class="form-select" id="user_role" name="role_id" wire:model="user.role_id">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endrole
                <div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" value="1" id="user_pvm" name="pvm"  wire:model="user.pvm">
                        <label class="form-check-label" for="user_pvm">{{ __('PVM') }}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button wire:click.prevent="submit" class="btn btn-primary">{{ __('Update') }}</button>
    </div>
</div>
