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
                {!! $order->customer_name !== '' ? $order->customer_name.'<br>' : '' !!}
                {!! $order->customer_email !== '' ? $order->customer_email.'<br>' : '' !!}
                {!! $order->customer_company_name !== '' ? $order->customer_company_name.'<br>' : '' !!}
                {!! $order->customer_company_address !== '' ? $order->customer_company_address.'<br>' : '' !!}
                {!! $order->customer_company_registration_code !== '' ? __('Registration code').': '.$order->customer_company_registration_code.'<br>' : '' !!}
                {!! $order->customer_company_vat_number !== '' ? __('VAT number').': '.$order->customer_company_vat_number.'<br>' : '' !!}
                {!! $order->customer_company_phone_number !== '' ? $order->customer_company_phone_number.'<br>' : '' !!}
            </td>
        </tr>
    </table>
</div>
<div class="invoice-items">
    <table>
        <tr>
            <th>#</th>
            <th>Pavadinimas</th>
            <th>Kodas</th>
            <th>Kiekis</th>
            <th>Kaina</th>
            <th>Suma</th>
        </tr>
        @foreach($order->items as $product)
            <tr class="invoice-elements">
                <td>{{ $loop->index + 1}}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->code }}</td>
                <td>{{ $product->qty }} {{ $product->units }}</td>
                <td>{{ price_format($product->priceWithPvm + $product->additional_fees) }}€</td>
                <td>{{ price_format(($product->priceWithPvm + $product->additional_fees) * $product->qty) }}€</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="4" rowspan="2" class="table-space">
                <strong>Bendra suma žodžiais:</strong><br>
                {{ $order->total_in_words }}
            </td>
            <td class="invoice-element">Bendra suma</td>
            <td class="invoice-element">{{ $order->total }}€</td>
        </tr>
        <tr>
            <td class="invoice-element" colspan="2" style="border: 0;text-align:right;">PVM: {{ $order->pvm }}%</td>
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
