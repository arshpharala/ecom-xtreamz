@php
  $brandName = config('app.name');
  $brandColor = '#257e89';
  $accentColor = '#5fa6ac';
  $textColor = '#333333';
  $logoUrl = asset('assets/images/logo-white.png');
  $customerName = $order->billingAddress->name ?? 'Customer';
@endphp

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>New Order Placed - {{ $brandName }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    @font-face {
      font-family: 'UAESymbol';
      src: url('/assets/fonts/font.woff2') format('woff2'),
        url('/assets/fonts/font.woff') format('woff'),
        url('/assets/fonts/font.ttf') format('truetype');
    }

    .dirham-symbol {
      font-family: 'UAESymbol', sans-serif;
      font-size: inherit;
      color: inherit;
    }

    body {
      margin: 0;
      padding: 0;
      background: #f5f6f8;
      font-family: Arial, Helvetica, sans-serif;
      color: {{ $textColor }};
    }

    .container {
      max-width: 700px;
      margin: 30px auto;
      background: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
    }

    .header {
      background: {{ $brandColor }};
      padding: 20px;
      text-align: center;
    }

    .header img {
      max-width: 160px;
    }

    .content {
      padding: 30px;
    }

    h1 {
      color: #111;
      font-size: 24px;
      margin-bottom: 10px;
    }

    p {
      color: #555;
      line-height: 1.6;
      font-size: 15px;
    }

    .order-summary {
      margin-top: 25px;
      border-top: 1px solid #eee;
      padding-top: 20px;
    }

    .order-id {
      color: {{ $accentColor }};
      font-weight: 600;
    }

    .details {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .details th,
    .details td {
      padding: 10px 8px;
      text-align: left;
      font-size: 14px;
    }

    .details th {
      color: #555;
      background: #f8f8f8;
    }

    .details td {
      border-bottom: 1px solid #eee;
    }

    .total {
      font-weight: bold;
    }

    .addresses {
      margin-top: 25px;
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      background: #f9f9f9;
    }

    .address-box {
      background: #f9f9f9;
      border-radius: 6px;
      padding: 15px 30px;
      font-size: 14px;
      width: 45%;
    }

    .footer {
      background: #1e293b;
      color: #bbb;
      text-align: center;
      padding: 20px;
      font-size: 12px;
    }

    .btn {
      display: inline-block;
      margin-top: 20px;
      padding: 14px 28px;
      background: {{ $accentColor }};
      color: #fff !important;
      text-decoration: none;
      font-weight: 600;
      border-radius: 5px;
    }

    .btn:hover {
      background: #1746b0;
    }

    @media(max-width:600px) {
      .addresses {
        flex-direction: column;
      }

      .address-box {
        width: 100%;
        margin-bottom: 10px;
      }
    }
  </style>
</head>

<body>

  <div class="container">
    <!-- Header -->
    <div class="header">
      <a href="{{ config('app.url') }}">
        <img src="{{ $logoUrl }}" alt="{{ $brandName }} Logo">
      </a>
    </div>

    <!-- Content -->
    <div class="content">
      <h1>New Order Alert!</h1>
      <p><strong>{{ $customerName }}</strong> placed order <span class="order-id">#{{ $order->reference_number }}</span> on {{ $order->created_at->format('M d, Y g:i A') }}.</p>

      <a href="{{ url('admin/orders/' . $order->id) }}" class="btn" target="_blank">
        View Order
      </a>

      <!-- Order Summary -->
      <div class="order-summary">
        <h3 style="margin-bottom:10px;">Order Summary</h3>
        <table class="details">
          <thead>
            <tr>
              <th>Product</th>
              <th>Qty</th>
              <th style="text-align:end">Price</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($order->lineItems as $item)
              <tr>
                <td>
                  {{ optional($item->productVariant)->product->translation->name ?? 'Product' }}
                </td>
                <td>{{ $item->quantity }}</td>
                <td style="text-align:end">
                  {!! price_format($order->currency->code, $item->price * $item->quantity, null, false) !!}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <table class="details">
          <tbody>
            <tr>
              <td style="text-align:start">Subtotal</td>
              <td style="text-align:end">{!! price_format($order->currency->code, $order->sub_total, null, false) !!}</td>
            </tr>
            <tr>
              <td style="text-align:start">Tax</td>
              <td style="text-align:end">{!! price_format($order->currency->code, $order->tax, null, false) !!}</td>
            </tr>
            <tr class="total">
              <td style="text-align:start">Total</td>
              <td style="text-align:end">{!! price_format($order->currency->code, $order->total, null, false) !!}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div style="margin-top: 25px; border-top: 1px solid #eee; padding-top: 20px;">
        <h3 style="margin-bottom:10px;">Payment Information</h3>
        <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
      </div>

      <!-- Addresses -->
      @php
        $shippingAddress = $order->shippingAddress ?: $order->billingAddress;
      @endphp
      <div class="addresses">
        <div class="address-box">
          <strong>Billing Address</strong><br>
          {!! $order->billingAddress?->render() ?? 'N/A' !!}
        </div>

        <div class="address-box">
          <strong>Shipping Address</strong><br>
          {!! $shippingAddress?->render() ?? 'N/A' !!}
        </div>
      </div>

    </div>

    <!-- Footer -->
    <div class="footer">
      <p>© {{ date('Y') }} {{ $brandName }} · All rights reserved</p>
    </div>
  </div>
</body>

</html>
