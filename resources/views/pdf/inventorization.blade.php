<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('Inventorization') }} {{ $inventorization->warehouse->name }}</title>
</head>
<body>
<div class="invoice-head">
    <img src="{{ public_path(setting('logo')) }}" class="inv-logo">
</div>

<div class="invoice-title">
    <h2>Inventorizacija</h2>
    <h3>{{ $inventorization->warehouse->name }}</h3>
    <p>{{ $inventorization->date }}</p>
</div>

<div class="invoice-top">
</div>

<div class="invoice-items">
    <table>
        <tr>
            <th>#</th>
            <th>Prekė</th>
            <th>Kodas</th>
            <th>Likutis</th>
            <th>Vienetai</th>
        </tr>
        @forelse($inventorization->items as $item)
            <tr class="invoice-elements">
                <td>{{ $loop->index + 1}}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->balance }}</td>
                <td>{{ $item->units }}</td>
            </tr>
        @empty
            <tr class="invoice-elements">
                <td colspan="5">Tuščia</td>
            </tr>
        @endforelse
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
