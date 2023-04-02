<table>
    <thead>
    <tr>
        <th>{{ __('Title') }}</th>
        <th>{{ __('Code') }}</th>
        <th>{{ __('Price') }}</th>
        <th>{{ __('Price with PVM') }}</th>
        <th>{{ __('Prime cost') }}</th>
        <th>{{ __('Quantity') }}</th>
        <th>{{ __('Amount') }}</th>
        <th>{{ __('Discount') }}</th>
        <th>{{ __('Total') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            <td>{{ $item['item']->name }}</td>
            <td>{{ $item['item']->code }}</td>
            <td>{{ $item['item']->price }} €</td>
            <td>{{ $item['item']->priceWithPvm }} €</td>
            <td>{{ $item['item']->prime_cost }} €</td>
            <td>{{ $item['quantity'] }} {{ $item['item']->units }}</td>
            <td>{{ $item['amount'] }} €</td>
            <td>
                @if(isset($item['discount']))
                    {{ $item['discount_size'] }}
                    {{ $item['discount']->type == 'percent' ? '%' : '€ / ' . $item['item']->units }}
                @else
                    0
                @endif
            </td>
            <td>{{ $item['total'] }} €</td>
        </tr>
    @endforeach
    </tbody>
</table>
