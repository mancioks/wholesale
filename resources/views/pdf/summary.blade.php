<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('Summary') }} {{ $order->number }}</title>
</head>
<body>
<div class="invoice-head">
    <img src="{{ public_path(setting('logo')) }}" class="inv-logo">
</div>

<div class="invoice-title">
    <h2>Suvestinė {{ $order->number }}</h2>
</div>

<div class="invoice-items">
    <table>
        <tr>
            <th>Eil. Nr.</th>
            <th>Prekė</th>
            <th>Kodas</th>
            <th>Mato vnt.</th>
            <th>Kiekis</th>
            <th>Kaina</th>
            <th>Suma</th>
        </tr>
        @foreach($order->items as $product)
            <tr class="invoice-elements">
                <td>{{ $loop->index + 1}}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->code }}</td>
                <td>{{ $product->units }}</td>
                <td>{{ $product->qty }}</td>
                <td>{{ $product->priceWithPvm }}€</td>
                <td>{{ $product->amount }}€</td>
            </tr>
        @endforeach
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
        padding: 0px 8px;
    }
    .invoice-items table th {
        vertical-align: middle;
    }
    .invoice-items table th{
        background: #ffffff;
        color: #000000;
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
    .border-bottom-1{
        border-bottom: 1px solid #000000;
        position: relative;
    }
    .border-bottom-transparent{
        border-bottom: 1px solid #fff;
    }
    .signature{
        position: absolute;
        height: 40px;
        top: -10px;
    }
</style>
</body>
</html>
