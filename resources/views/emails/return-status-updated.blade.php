@php
  $brandName = $brandName ?? config('app.name');
  $brandColor = $brandColor ?? '#257e89';
  $accentColor = $accentColor ?? '#5fa6ac';
  $textColor = $textColor ?? '#333333';
  $logoUrl = $logoUrl ?? asset('assets/images/logo-white.png');
  $supportEmail = $supportEmail ?? 'inquest@xtremez.xyz';

  $statusColors = [
      'pending' => '#ffc107',
      'approved' => '#17a2b8',
      'rejected' => '#dc3545',
      'shipped' => '#007bff',
      'received' => '#17a2b8',
      'refunded' => '#28a745',
  ];
  $statusColor = $statusColors[$returnRequest->status] ?? $accentColor;
@endphp

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Return Status Updated - {{ $brandName }}</title>
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

    .status-banner {
      padding: 15px;
      background: {{ $statusColor }};
      color: #fff;
      text-align: center;
      font-weight: bold;
      border-radius: 5px;
      margin-top: 20px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .admin-note {
      background: #f8f9fa;
      border-left: 4px solid {{ $statusColor }};
      padding: 15px;
      margin-top: 20px;
      font-style: italic;
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
      <h1>Return Status Updated</h1>
      <p>Hi {{ $returnRequest->user->name }},</p>
      <p>The status of your return request <span class="highlight">#{{ $returnRequest->reference_number }}</span> has
        been updated.</p>

      <div class="status-banner">
        Status: {{ str_replace('_', ' ', $returnRequest->status) }}
      </div>

      @if ($returnRequest->admin_notes)
        <div class="admin-note">
          <strong>Note from our team:</strong><br>
          {{ $returnRequest->admin_notes }}
        </div>
      @endif

      <div class="summary-box">
        <h3>Request Summary</h3>
        <p><strong>Order:</strong> #{{ $returnRequest->order->reference_number }}</p>
        <p><strong>Reason:</strong> {{ $returnRequest->reason->reason }}</p>
        <p><strong>Refund Method:</strong> {{ str_replace('_', ' ', $returnRequest->refund_method) }}</p>
      </div>

      <a href="{{ route('customers.orders.show', $returnRequest->order_id) }}" class="btn">
        View Return History
      </a>

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
