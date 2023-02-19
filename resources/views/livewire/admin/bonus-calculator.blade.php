<div>
    <table class="table table-bordered mb-0">
        <thead>
        <tr class="bg-secondary text-white">
            <th scope="col">{{ __('Material') }}</th>
            <th scope="col">{{ __('Price') }}</th>
            <th scope="col">{{ __('Quantity') }}</th>
            <th scope="col">{{ __('Used at') }}</th>
            <th scope="col">{{ __('Rule') }}</th>
            <th scope="col">{{ __('Actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($calculation->data as $calculationData)
            <tr>
                <td>{{ $calculationData->material }}</td>
                <td>{{ $calculationData->price }}</td>
                <td>{{ $calculationData->quantity }}</td>
                <td>{{ $calculationData->used_at }}</td>
                <td>
                    <select class="form-select form-select-sm" wire:model="calculationDataRules.{{ $calculationData->id }}">
                        <option value="">{{ __('None') }}</option>
                        @foreach($calculationRules as $calculationRule)
                            <option value="{{ $calculationRule->id }}">{{ $calculationRule->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $calculationData->id }})">{{ __('Delete') }}</button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">{{ __('Empty') }}</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
