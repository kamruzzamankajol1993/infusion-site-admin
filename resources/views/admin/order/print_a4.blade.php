<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 14px; color: #333; }
        table { width: 100%; border-collapse: collapse; }
        .header { margin-bottom: 30px; }
        .invoice-title { font-size: 24px; font-weight: bold; color: #444; text-align: right; }
        .company-info { font-size: 12px; line-height: 1.4; }
        .customer-info { margin-top: 20px; font-size: 13px; }
        .items-table { margin-top: 30px; width: 100%; }
        .items-table th { background-color: #f0f0f0; border: 1px solid #ddd; padding: 10px; text-align: left; }
        .items-table td { border: 1px solid #ddd; padding: 10px; }
        .totals-table { width: 40%; margin-left: auto; margin-top: 20px; }
        .totals-table td { padding: 5px; text-align: right; }
        .total-row { font-weight: bold; font-size: 16px; background-color: #f9f9f9; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td width="60%">
                <h2 style="margin: 0; color: #0098de;">{{ config('app.name', 'Optifusion Inc') }}</h2>
                <div class="company-info">
                    Dhaka, Bangladesh<br>
                    Phone: +880 1234 567890<br>
                    Email: support@optifusion.com
                </div>
            </td>
            <td width="40%" class="invoice-title">
                INVOICE<br>
                <span style="font-size: 14px; font-weight: normal;">#{{ $order->order_number }}</span><br>
                <span style="font-size: 12px; font-weight: normal;">Date: {{ $order->created_at->format('d M, Y') }}</span>
            </td>
        </tr>
    </table>

    <div class="customer-info">
        <strong>Bill To:</strong><br>
        {{ $order->first_name }} {{ $order->last_name }}<br>
        {{ $order->shipping_address }}<br>
        {{ $order->city }} - {{ $order->zip_code }}<br>
        Phone: {{ $order->phone }}
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="50%">Product Description</th>
                <th width="15%" style="text-align: right;">Price</th>
                <th width="10%" style="text-align: center;">Qty</th>
                <th width="20%" style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    {{ $item->product_name }}
                    @if($item->variation_name) <br><small>({{ $item->variation_name }})</small> @endif
                </td>
                <td style="text-align: right;">{{ number_format($item->price, 2) }}</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td style="text-align: right;">{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td>Subtotal:</td>
            <td>{{ number_format($order->sub_total, 2) }}</td>
        </tr>
        @if($order->shipping_cost > 0)
        <tr>
            <td>Shipping:</td>
            <td>{{ number_format($order->shipping_cost, 2) }}</td>
        </tr>
        @endif
        @if($order->discount > 0)
        <tr>
            <td>Discount:</td>
            <td>-{{ number_format($order->discount, 2) }}</td>
        </tr>
        @endif
        <tr class="total-row">
            <td style="border-top: 2px solid #333;">Total:</td>
            <td style="border-top: 2px solid #333;">{{ number_format($order->grand_total, 2) }}</td>
        </tr>
    </table>
</body>
</html>