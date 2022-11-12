<button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#paymentForm">
    <i class="bi bi-cash-coin"></i> {{ __('Payments') }}
</button>
<div class="modal fade" id="paymentForm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg text-black">
        <div class="modal-content">
            <form action="{{ route('payments.store', $order) }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Payments') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table mt-3">
                        <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ __('Method') }}</th>
                            <th scope="col">{{ __('Date') }}</th>
                            <th scope="col">{{ __('Amount') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($order->payments as $payment)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>{{ $payment->method->name }}</td>
                                <td>{{ $payment->created_at }}</td>
                                <td>{{ price_format($payment->amount) }}€</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">{{ __('No payments') }}</td>
                            </tr>
                        @endforelse
                        <tr>
                            <td colspan="3" class="border-0"></td>
                            <td><b>{{ __('Paid') }}</b>: {{ price_format($order->paid_total) }}€</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="border-0"></td>
                            <td><b>{{ __('Left to pay') }}</b>: {{ price_format($order->total - $order->paid_total) }}€</td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="amount" class="col-form-label">{{ __('Amount') }}</label>
                            <input type="number" id="amount" class="form-control" name="amount" placeholder="{{ $order->total }}" step=".01">
                        </div>
                        <div class="col-lg-6">
                            <label for="payment_method" class="col-form-label">{{ __('Payment method') }}</label>
                            <select class="form-select" name="payment_method_id" id="payment_method">
                                @foreach($paymentMethods as $paymentMethod)
                                    <option value="{{ $paymentMethod->id }}" {{ $order->paymentMethod && $paymentMethod->id === $order->paymentMethod->id ? 'selected':'' }}>{{ $paymentMethod->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Add') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
