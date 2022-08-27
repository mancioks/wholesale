<h1>Sveiki,</h1>
<p>{{ $content }}</p>

@if(isset($data->order))
    <p><a href="{{ route('order.show', $data->order) }}">Peržiūrėti užsakymą</a></p>
@endif
