<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#calculator-services-modal">
    {{ __('Services') }}
</button>

<div class="modal modal-xl fade" id="calculator-services-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @livewire('admin.calculator.services')
        </div>
    </div>
</div>
