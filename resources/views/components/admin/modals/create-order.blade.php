<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#create-order-modal">
    <i class="bi bi-plus-circle me-1"></i>
    {{ __('Create order') }}
</button>

<div class="modal modal-xl fade" id="create-order-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @livewire('admin.create-order')
        </div>
    </div>
</div>
