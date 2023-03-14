<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#calculator-create-template-modal">
    <i class="bi bi-plus-circle"></i>
    {{ __('Create template') }}
</button>

<div class="modal modal-xl fade" id="calculator-create-template-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @livewire('admin.calculator.create-template')
        </div>
    </div>
</div>
