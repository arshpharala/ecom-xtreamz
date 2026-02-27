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
  <title>@yield('title', $brandName)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      margin: 0;
      padding: 0;
      background: #f2f4f8;
      font-family: Arial, Helvetica, sans-serif;
      color: {{ $textColor }};
    }

    .container {
      max-width: 600px;
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
      padding: 30px 25px;
    }

    .content h1 {
      margin: 0 0 15px;
      font-size: 24px;
      color: #111;
      text-align: center;
    }

    .content p {
      margin: 0 0 18px;
      font-size: 15px;
      line-height: 1.5;
      color: #555;
    }

    .btn {
      display: inline-block;
      padding: 12px 28px;
      background: {{ $accentColor }};
      color: #fff !important;
      font-size: 15px;
      font-weight: 600;
      border-radius: 6px;
      text-decoration: none;
      margin: 15px 0;
    }

    .btn:hover {
      background: #1746b0;
    }

    .details-table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      background: #f9f9f9;
      border-radius: 8px;
      overflow: hidden;
    }

    .details-table td {
      padding: 12px 15px;
      border-bottom: 1px solid #eee;
      font-size: 14px;
    }

    .details-table td:first-child {
      font-weight: bold;
      color: #444;
      width: 30%;
    }

    .message-box {
      background: #f8f8f8;
      padding: 15px;
      border-radius: 6px;
      border-left: 4px solid {{ $brandColor }};
      margin-top: 10px;
      font-style: italic;
      color: #555;
      white-space: pre-wrap;
    }

    .footer {
      background: #1e293b;
      padding: 20px;
      text-align: center;
      font-size: 12px;
      color: #bbb;
    }

    .footer a {
      margin: 0 8px;
      color: #bbb;
      text-decoration: none;
    }
  </style>
  @stack('styles')
</head>

<body>

  <div class="container">
    <div class="header">
      <a href="{{ config('app.url') }}">
        <img src="{{ $logoUrl }}" alt="{{ $brandName }} Logo">
      </a>
    </div>

    <div class="content">
      @yield('content')
    </div>

    <div class="footer">
      <p>
        <a href="#">Facebook</a> ·
        <a href="#">Instagram</a> ·
        <a href="#">Twitter</a>
      </p>
      <p>© {{ date('Y') }} {{ $brandName }} · All rights reserved</p>
    </div>
  </div>

</body>

</html>
