<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#create-inventorization-modal">
    <i class="bi bi-plus-circle me-1"></i>
    {{ __('Create inventorization') }}
</button>

<div class="modal modal-xl fade" id="create-inventorization-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.inventorization.add') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Create inventorization') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div>
                                <label class="form-label" for="warehouse_id">{{ __('Warehouse') }}</label>
                                <select name="warehouse_id" id="warehouse_id" class="form-select" required>
                                    <option value="" disabled selected>{{ __('Select warehouse') }}</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-2">
                                <label class="form-label" for="date">{{ __('Date') }}</label>
                                <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
