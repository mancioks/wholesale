<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#create-order-modal">
    <i class="bi bi-plus-circle me-1"></i>
    {{ __('Create order') }}
</button>

<div class="modal modal-xl fade" id="create-order-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Create order') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @livewire('admin.create-order')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
