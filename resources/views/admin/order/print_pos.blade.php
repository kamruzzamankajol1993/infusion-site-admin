<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; margin: 0; padding: 0; color: #000; }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .divider { border-bottom: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; }
        td { vertical-align: top; padding: 2px 0; }
    </style>
</head>
<body>
    <div class="center">
        <h3 style="margin: 0;">{{ config('app.name') }}</h3>
        <div>Dhaka, Bangladesh</div>
        <div>Tel: +880 1234 567890</div>
    </div>
    
    <div class="divider"></div>
    
    <div>
        <strong>Order:</strong> #{{ $order->order_number }}<br>
        <strong>Date:</strong> {{ $order->created_at->format('d-m-y H:i') }}<br>
        <strong>Cust:</strong> {{ $order->first_name }} {{ $order->last_name }}<br>
        <strong>Phone:</strong> {{ $order->phone }}
    </div>

    <div class="divider"></div>

    <table>
        @foreach($order->items as $item)
        <tr>
            <td colspan="2" class="bold">{{ $item->product_name }} @if($item->variation_name)({{ $item->variation_name }})@endif</td>
        </tr>
        <tr>
            <td>{{ $item->quantity }} x {{ number_format($item->price, 2) }}</td>
            <td class="right">{{ number_format($item->total, 2) }}</td>
        </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <table>
        <tr>
            <td>Subtotal</td>
            <td class="right">{{ number_format($order->sub_total, 2) }}</td>
        </tr>
        @if($order->shipping_cost > 0)
        <tr>
            <td>Shipping</td>
            <td class="right">{{ number_format($order->shipping_cost, 2) }}</td>
        </tr>
        @endif
        @if($order->discount > 0)
        <tr>
            <td>Discount</td>
            <td class="right">-{{ number_format($order->discount, 2) }}</td>
        </tr>
        @endif
        <tr class="bold" style="font-size: 12px;">
            <td>TOTAL</td>
            <td class="right">{{ number_format($order->grand_total, 2) }}</td>
        </tr>
    </table>

    <div class="divider"></div>
    <div class="center" style="margin-top: 5px;">Thank you for shopping!</div>
</body>
</html>