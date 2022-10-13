<h1>Sveiki,</h1>
<p>Jūs pateikėte naują užsakymą.</p>
@if($order->pre_invoice_required)
    <p>Peržiūrėkite prisegtą išankstinę sąskaitą.</p>
@endif
<p><a href="{{ route('order.show', $order) }}">Peržiūrėti užsakymą</a></p>
