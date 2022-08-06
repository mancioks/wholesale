<h1>Sveiki,</h1>
<p>Jūsų užsakymas {{ $order->number }} užbaigtas.</p>
{{-- <p>Sąskaita prisegtuke.</p> --}}
<p><a href="{{ route('order.show', $order) }}">Peržiūrėti užsakymą</a></p>
