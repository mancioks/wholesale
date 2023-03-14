<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#calculator-managers-modal">
    {{ __('Managers') }}
</button>

<div class="modal modal-xl fade" id="calculator-managers-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @livewire('admin.calculator.managers')
        </div>
    </div>
</div>
