<h1>Sveiki,</h1>
<p>Naujas užsakymas nuo {{ $order->user->name }} ({{ $order->user->details->company_name }}).</p>
<p><a href="{{ route('order.show', $order) }}">Peržiūrėti užsakymą</a></p>
