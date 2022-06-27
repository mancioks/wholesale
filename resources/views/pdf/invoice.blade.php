<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('Order') }} {{ $order->number }}</title>
</head>
<body>
<div class="invoice-head">
    <img src="{{ public_path(setting('logo')) }}" class="inv-logo">
</div>

<div class="invoice-title">
    <h2>Išankstinė sąskaita apmokėjimui</h2>
    <h3>{{ $order->number }}</h3>
    <p>{{ $order->created_at->format('Y-m-d') }}</p>
</div>

<div class="invoice-top">
    <table class="invoice-subjects">
        <tr>
            <td>
                PARDAVĖJAS<br>
                {!! nl2br(setting('company.details')) !!}
            </td>
            <td>
                PIRKĖJAS<br>
                {{ $order->user->name }}, {{ $order->user->email }}<br>
                {{ $order->user->details->company_name }}<br>
                {{ $order->user->details->address }}<br>
                Įmonės kodas: {{ $order->user->details->registration_code }}<br>
                PVM kodas: {{ $order->user->details->vat_number }}<br>
                {{ $order->user->details->phone_number }}<br>
            </td>
        </tr>
    </table>
</div>
<div class="invoice-items">
    <table>
        <tr>
            <th>#</th>
            <th>Pavadinimas</th>
            <th>Kiekis</th>
            <th>Kaina</th>
            <th>Suma</th>
        </tr>
        @foreach($order->items as $product)
            <tr class="invoice-elements">
                <td>{{ $loop->index + 1}}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->qty }} {{ $product->product->units }}</td>
                <td>{{ $product->price }}€</td>
                <td>{{ $product->amount }}€</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td colspan="3" rowspan="3" class="table-space">
                <strong>Bendra suma žodžiais:</strong><br>
                {{ $order->total_in_words }}
            </td>
            <td class="invoice-element">Suma</td>
            <td class="invoice-element">{{ $order->amount }}€</td>
        </tr>
        <tr>
            <td class="invoice-element">PVM ({{ $order->pvm }}%)</td>
            <td class="invoice-element">{{ $order->pvm_total }}€</td>
        </tr>
        <tr>
            <td class="invoice-element">Bendra suma</td>
            <td class="invoice-element">{{ $order->total }}€</td>
        </tr>
    </table>
</div>
<style>
    body { font-family: DejaVu Sans, sans-serif; }
    *{
        font-size: 12px;
    }
    .invoice-top table{
        width: 100%;
    }
    .invoice-top table tr td{
        width: calc(100% / 3) !important;
    }
    .invoice-title *{
        text-align: center;
    }
    .invoice-head{
        border-bottom: 2px solid #000000;
        margin-bottom: 15px;
    }
    table, table * {
        border-collapse: collapse;
        padding: 0;
        margin: 0;
        border: 0;
        text-align: left;
        vertical-align: top;
    }
    .invoice-title h3, .invoice-title h2{
        margin: 0;
        padding: 0;
    }
    .invoice-items table{
        width: 100%;
        margin-top: 30px;
    }
    .invoice-items table td, .invoice-items table th{
        padding: 8px;
    }
    .invoice-items table th{
        background: #000;
        color: #ffffff;
    }
    .invoice-items table tr.invoice-elements td, td.invoice-element, .invoice-items table th{
        border: 1px solid #000000;
    }
    .table-space{
        max-width: 200px;
        vertical-align: bottom;
    }
    .inv-logo{
        height: 60px;
        margin-bottom: 20px;
    }
</style>
</body>
</html>
