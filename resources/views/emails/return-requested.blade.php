@php
  $brandName = $brandName ?? config('app.name');
  $brandColor = $brandColor ?? '#257e89';
  $accentColor = $accentColor ?? '#5fa6ac';
  $textColor = $textColor ?? '#333333';
  $logoUrl = $logoUrl ?? asset('assets/images/logo-white.png');
  $supportEmail = $supportEmail ?? 'inquest@xtremez.xyz';
@endphp

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Return Request Received - {{ $brandName }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
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

    .summary-box {
      margin-top: 25px;
      border-top: 1px solid #eee;
      padding-top: 20px;
    }

    .highlight {
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

    .footer {
      background: #1e293b;
      color: #bbb;
      text-align: center;
      padding: 20px;
      font-size: 12px;
    }

    .footer a {
      color: #bbb;
      text-decoration: none;
      margin: 0 5px;
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

    .status-badge {
      display: inline-block;
      padding: 4px 12px;
      background: #fff3cd;
      color: #856404;
      border-radius: 20px;
      font-size: 12px;
      font-weight: bold;
      text-transform: uppercase;
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
      @if ($type === 'sales')
        <h1>New Return Request: #{{ $returnRequest->reference_number }}</h1>
        <p>A new return request has been submitted by <strong>{{ $returnRequest->user->name }}</strong>.</p>
      @else
        <h1>Return Request Received</h1>
        <p>Hi {{ $returnRequest->user->name }},</p>
        <p>We've received your return request <span class="highlight">#{{ $returnRequest->reference_number }}</span>
          for Order #{{ $returnRequest->order->reference_number }}.</p>
        <p>Our team is currently reviewing your request. We will notify you once there is an update.</p>
      @endif

      <div class="summary-box">
        <h3>Request Details</h3>
        <p><strong>Status:</strong> <span class="status-badge">Under Review</span></p>
        <p><strong>Reason:</strong> {{ $returnRequest->reason->reason }}</p>
        @if ($returnRequest->description)
          <p><strong>Description:</strong> {{ $returnRequest->description }}</p>
        @endif

        <table class="details">
          <thead>
            <tr>
              <th>Product</th>
              <th style="text-align:center">Qty</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($returnRequest->items as $item)
              <tr>
                <td>{{ $item->orderLineItem->productVariant->product->translation->name ?? 'Product' }}</td>
                <td style="text-align:center">{{ $item->quantity }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      @if ($type === 'customer')
        <a href="{{ route('customers.profile', ['tab' => 'returns']) }}" class="btn">
          Track Return Status
        </a>
      @else
        <a href="{{ route('admin.sales.return-requests.show', $returnRequest->id) }}" class="btn">
          Review Request
        </a>
      @endif

      <p style="margin-top:30px; font-size:14px; color:#666;">
        If you have any questions, reply to this email or contact us at
        <a href="mailto:{{ $supportEmail }}" style="color:{{ $accentColor }}">{{ $supportEmail }}</a>.
      </p>
    </div>

    <!-- Footer -->
    <div class="footer">
      <p>© {{ date('Y') }} {{ $brandName }} · All rights reserved</p>
    </div>
  </div>
</body>

</html>
