<h1>Sveiki,</h1>
<p>Klientas {{ $order->user->name }} atšaukė užsakymą {{ $order->number }}.</p>
<p><a href="{{ route('order.show', $order) }}">Peržiūrėti užsakymą</a></p>
