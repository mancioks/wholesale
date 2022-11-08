<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#create-product-modal">
    <i class="bi bi-plus-circle me-1"></i>
    {{ __('Create product') }}
</button>

<div class="modal modal-xl fade" id="create-product-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @livewire('admin.create-product')
        </div>
    </div>
</div>
