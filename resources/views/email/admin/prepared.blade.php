<h1>Sveiki,</h1>
<p>Užsakymas {{ $order->number }} ({{ $order->user->details->company_name }}) suruoštas.</p>
<p><a href="{{ route('order.show', $order) }}">Peržiūrėti užsakymą</a></p>
