<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#edit-order-modal">
    <i class="bi bi-pencil-fill me-1"></i>
    {{ __('Edit order') }}
</button>

<div class="modal modal-xl fade" id="edit-order-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @livewire('admin.edit-order', ['order' => $order])
        </div>
    </div>
</div>
