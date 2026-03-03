<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Order Receipt - {{ $order->reference_number }}</title>
  <style>
    @page {
      /* Reduced margins to maximize space */
      margin: 25px 35px;
    }

    body {
      font-family: Helvetica, Arial, sans-serif;
      font-size: 11px;
      color: #333;
      line-height: 1.4;
      margin: 0;
      padding: 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    /* Header */
    .header-table td {
      vertical-align: top;
    }

    .logo {
      max-height: 40px;
      margin-bottom: 5px;
    }

    .company-details {
      font-size: 9px;
      color: #666;
      line-height: 1.2;
    }

    .receipt-title {
      font-size: 20px;
      margin: 0;
      font-weight: normal;
      color: #000;
      letter-spacing: 0.5px;
    }

    .order-meta {
      margin-top: 4px;
      font-size: 10px;
      color: #444;
    }

    /* Info Blocks (Bill/Ship/Payment) */
    .info-table {
      margin-top: 30px;
      margin-bottom: 40px;
    }

    .info-table td {
      vertical-align: top;
      width: 33.33%;
    }

    .section-label {
      font-size: 9px;
      color: #9a9a9a;
      /* Specific grey from image */
      margin-bottom: 6px;
      display: block;
      text-transform: uppercase;
      letter-spacing: 0.8px;
    }

    .info-content {
      color: #000;
      padding-right: 15px;
    }

    /* Items Table Alignment */
    .items-table th {
      border-bottom: 1.5px solid #333;
      color: #000;
      padding: 8px 0;
      font-size: 9px;
      font-weight: normal;
      text-transform: uppercase;
    }

    .items-table td {
      padding: 12px 0;
      border-bottom: 1px solid #f0f0f0;
      vertical-align: top;
    }

    /* Column Widths & Alignments */
    .col-desc {
      width: 50%;
      text-align: left;
    }

    .col-qty {
      width: 10%;
      text-align: center;
    }

    .col-price {
      width: 20%;
      text-align: right;
    }

    .col-total {
      width: 20%;
      text-align: right;
    }

    /* Totals Block */
    .totals-container {
      margin-top: 20px;
    }

    .totals-table {
      width: 38%;
      /* Adjusted to match image visual flow */
      float: right;
    }

    .totals-table td {
      padding: 4px 0;
    }

    .label-cell {
      text-align: right;
      padding-right: 30px;
      color: #666;
    }

    .amount-cell {
      text-align: right;
      color: #000;
      /* Fixed width to ensure vertical alignment of currency values */
      width: 100px;
    }

    .grand-total-row td {
      border-top: 1px solid #999;
      padding-top: 10px;
      margin-top: 5px;
    }

    .grand-total-label {
      font-size: 11px;
      text-transform: uppercase;
      color: #000;
    }

    .grand-total-amount {
      font-size: 13px;
      font-weight: bold;
      color: #000;
    }

    .clear {
      clear: both;
    }
  </style>
</head>

<body>

  <table class="header-table">
    <tr>
      <td>
        <img src="{{ public_path('assets/images/logo.png') }}" class="logo">
        <div class="company-details">
          {{ setting('site_title', 'Xtremez') }}<br>
          @if (setting('trn'))
            VAT: {{ setting('trn') }}<br>
          @endif
          {{ setting('contact_email', 'xtremez.ads@gmail.com') }} | {{ setting('contact_phone', '+971 52 262 1345') }}
        </div>
      </td>
      <td style="text-align: right;">
        <h1 class="receipt-title">ORDER RECEIPT</h1>
        <div class="order-meta">
          Date: {{ $order->created_at->format('d M Y') }}<br>
          Number: {{ $order->reference_number }}
        </div>
      </td>
    </tr>
  </table>

  @php
    $shippingAddress = $order->shippingAddress ?: $order->billingAddress;
  @endphp

  <table class="info-table">
    <tr>
      <td>
        <span class="section-label">Bill To</span>
        <div class="info-content">
          {!! $order->billingAddress ? $order->billingAddress->render() : 'N/A' !!}
        </div>
      </td>
      <td>
        <span class="section-label">Ship To</span>
        <div class="info-content">
          {!! $shippingAddress ? $shippingAddress->render() : 'N/A' !!}
          @if ($shippingAddress?->map_url)
            <div style="margin-top: 6px; font-size: 9px;">
              Map: {{ $shippingAddress?->map_url }}
            </div>
          @endif
        </div>
      </td>
      <td>
        <span class="section-label">Additional</span>
        <div class="info-content">
          Status: Paid<br>
          @if ($order->external_reference)
            Ref: {{ $order->external_reference }}<br>
          @endif
          Method: {{ $order->payment_method ? ucfirst($order->payment_method) : 'Card' }}
        </div>
      </td>
    </tr>
  </table>

  <table class="items-table">
    <thead>
      <tr>
        <th class="col-desc">Description</th>
        <th class="col-qty">Qty</th>
        <th class="col-price">Price</th>
        <th class="col-total">Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($order->lineItems as $item)
        <tr>
          <td class="col-desc">
            {{ optional($item->productVariant)->product->translation->name ?? 'Product' }}
            @if ($item->productVariant && $item->productVariant->attributeValues->count() > 0)
              <div style="font-size: 8px; color: #888; margin-top: 3px;">
                @foreach ($item->productVariant->attributeValues as $val)
                  {{ $val->attribute->name }}: {{ $val->value }}{{ !$loop->last ? ' | ' : '' }}
                @endforeach
              </div>
            @endif
          </td>
          <td class="col-qty">{{ $item->quantity }}</td>
          <td class="col-price">
            {!! price_format($order->currency->code, $item->price, null, false) !!}
          </td>
          <td class="col-total">
            {!! price_format($order->currency->code, $item->price * $item->quantity, null, false) !!}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="totals-container">
    <table class="totals-table">
      <tr>
        <td class="label-cell">Subtotal</td>
        <td class="amount-cell">{!! price_format($order->currency->code, $order->sub_total, null, false) !!}</td>
      </tr>
      @php $discount = $order->couponUsages->sum('discount_amount'); @endphp
      @if ($discount > 0)
        <tr>
          <td class="label-cell">Discount</td>
          <td class="amount-cell">-{!! price_format($order->currency->code, $discount, null, false) !!}</td>
        </tr>
      @endif
      <tr>
        <td class="label-cell">Tax</td>
        <td class="amount-cell">{!! price_format($order->currency->code, $order->tax, null, false) !!}</td>
      </tr>
      <tr class="grand-total-row">
        <td class="label-cell grand-total-label">Total</td>
        <td class="amount-cell grand-total-amount">{!! price_format($order->currency->code, $order->total, null, false) !!}</td>
      </tr>
    </table>
    <div class="clear"></div>
  </div>

</body>

</html>
