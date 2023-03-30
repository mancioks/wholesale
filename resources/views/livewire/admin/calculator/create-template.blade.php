<div>
    <div class="modal-header">
        <h5 class="modal-title">{{ __('Create template') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-6">
                <label for="name" class="form-label">{{ __('Template name') }}</label>
                <input type="text" wire:model="name" id="name" class="form-control">
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-6">
                <label for="calculatorPricePeriodId" class="form-label">{{ __('Select pricer') }}</label>
                <select class="form-select" wire:model="calculatorPricePeriodId" id="calculatorPricePeriodId">
                    <option value="" disabled selected>{{ __('Select pricer') }}</option>
                    @if($pricePeriods)
                        @foreach($pricePeriods as $pricePeriod)
                            <option value="{{ $pricePeriod->id }}">{{ $pricePeriod->name }} ({{ $pricePeriod->from }} - {{ $pricePeriod->to }})</option>
                        @endforeach
                    @endif
                </select>
                @error('calculatorPricePeriodId') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button class="btn btn-primary" wire:click="create">{{ __('Create') }}</button>
    </div>
</div>
