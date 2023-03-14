<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#calculator-installers-modal">
    {{ __('Installers') }}
</button>

<div class="modal modal-xl fade" id="calculator-installers-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @livewire('admin.calculator.installers')
        </div>
    </div>
</div>
