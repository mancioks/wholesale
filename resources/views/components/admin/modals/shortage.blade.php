<button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#shortageForm">
    <i class="bi bi-exclamation-triangle"></i> {{ __('Shortage') }}
</button>

<div class="modal fade" id="shortageForm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg text-black">
        <div class="modal-content">
            <form action="{{ route('order.shortage', $order) }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Shortage') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table mt-3">
                        <thead class="table-dark">
                        <tr>
                            <th scope="col">{{ __('Product name') }}</th>
                            <th scope="col">{{ __('Quantity') }}</th>
                            <th scope="col">{{ __('Stock') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->items as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->qty }} {{ $product->units }}</td>
                                <td>
                                    <input type="hidden" name="product[]" value="{{ $product->id }}">
                                    <input type="number" class="form-control form-control-sm" name="stock[]" placeholder="{{ __('Stock') }}" value="{{ $product->stock }}">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
