<div>
    <table class="table table-bordered mb-0">
        <thead>
        <tr class="bg-secondary text-white">
            <th scope="col">#</th>
            <th scope="col">{{ __('Name') }}</th>
            <th scope="col">{{ __('Quantity') }}</th>
            <th scope="col">{{ __('Action') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($template->items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->service->name }}</td>
                <td>{{ $item->quantity }} {{ $item->service->units }}</td>
                <td>
                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $item->id }})">{{ __('Delete') }}</button>
                </td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td>
                <div>
                    <select class="form-select select2" wire:model="selected.service" id="service" data-placeholder="{{ __('Select service') }}" style="width:100%">
                        <option></option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                    @error('selected.service') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <input type="number" class="form-control" step="0.01" wire:model="selected.quantity">
                    <span class="input-group-text">{{ $selected['units'] }}</span>
                </div>
                @error('selected.quantity') <span class="text-danger">{{ $message }}</span> @enderror
            </td>
            <td>
                <button class="btn btn-sm btn-success" wire:click="add">{{ __('Add') }}</button>
            </td>
        </tr>
        </tbody>
    </table>
</div>

@section('scripts')
    <script>
        $(document).ready(function() {
            var elementId = 'service';
            $('#' + elementId).on('change', function(e) {
                Livewire.emit(elementId + 'Listener', $('#' + elementId).select2("val"));
            });
        });
    </script>
@endsection


