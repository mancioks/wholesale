<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('Waybill') }} {{ $order->number }}</title>
</head>
<body>
<div class="invoice-head">
    <img src="{{ public_path(setting('logo')) }}" class="inv-logo">
</div>

<div class="invoice-title">
    <h2>Krovinio važtaraštis</h2>
    <p>Serija <u>VNVDV</u> Nr. <u>{{ substr($order->number, 1) }}</u></p>
    <p>Vieta: <u>{{ $order->warehouse->address }}</u> Data: <u>{{ $order->updated_at->format('Y-m-d') }}</u></p>
</div>

<div class="invoice-top">
    <table class="invoice-subjects">
        <tr>
            <td style="padding-right: 20px;width: 350px; !important;">
                <b>SIUNTĖJAS</b><br>
                @foreach(preg_split("/\r\n|\r|\n/", setting('company.details')) as $line)
                    <div class="border-bottom-1">{{ $line }}</div>
                @endforeach
            </td>
            <td>
                <b>GAVĖJAS</b><br>
                <div class="border-bottom-1">Įm. kodas/gim. data: </div>
                <div class="border-bottom-1">Adresas: </div>
                <div class="border-bottom-transparent">&nbsp;</div>
                <b>VEŽĖJAS</b><br>
                <div class="border-bottom-1">Įm. kodas: </div>
                <div class="border-bottom-1">Adresas: </div>
                <div class="border-bottom-1">Transportavimo apmokėjimas: </div>
            </td>
        </tr>
    </table>
</div>

<div class="invoice-items">
    <table>
        <tr>
            <th>Eil. Nr.</th>
            <th>Krovinio pavadinimas</th>
            <th>Mato vnt.</th>
            <th>Kiekis</th>
            <th>Kaina</th>
            <th>Suma</th>
        </tr>
        @foreach($order->items as $product)
            <tr class="invoice-elements">
                <td>{{ $loop->index + 1}}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->units }}</td>
                <td>{{ $product->qty }}</td>
                <td>{{ $product->price }}€</td>
                <td>{{ $product->amount }}€</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4" rowspan="3" class="table-space"></td>
            <td class="invoice-element">Iš viso</td>
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

<div class="invoice-top" style="margin-top: 20px;">
    <table class="invoice-subjects">
        <tr>
            <td style="padding-right: 20px;width: 350px; !important;">
                <div class="border-bottom-1">Krovinį išdavė: {{ $order->acceptedByUser->name }}</div>
                <div class="border-bottom-1">Transporto priemonės vairuotojas: </div>
            </td>
            <td>
                <div class="border-bottom-1">
                    Transportuoti gavo:
                    {{ $order->user->name }}
                    <img src="{{ public_path($order->signature) }}" class="signature">
                </div>
                <div class="border-bottom-1">Transporto priemonė: </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="border-bottom-1">
                    Krovinio pakrovimo vieta, adresas, išgabenimo data, laikas:
                    {{ $order->warehouse->name }},
                    {{ $order->warehouse->address }},
                    {{ $order->updated_at->format('Y-m-d') }},
                    {{ $order->updated_at->format('H:i') }}
                </div>
                <div class="border-bottom-1"><b><i>Krovinio iškrovimo vieta, iškrovimo data, laikas:</i></b></div>
            </td>
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
