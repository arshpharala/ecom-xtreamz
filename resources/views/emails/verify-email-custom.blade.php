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
  <title>Verify Your Email</title>
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
      text-align: center;
    }

    .content h1 {
      margin: 0 0 15px;
      font-size: 24px;
      color: #111;
    }

    .content p {
      margin: 0 0 18px;
      font-size: 15px;
      line-height: 1.5;
      color: #555;
    }

    .btn {
      display: inline-block;
      padding: 14px 32px;
      background: {{ $accentColor }};
      color: #fff !important;
      font-size: 15px;
      font-weight: 600;
      border-radius: 6px;
      text-decoration: none;
      margin: 25px 0;
    }

    .btn:hover {
      background: #1746b0;
    }

    .help {
      font-size: 13px;
      line-height: 1.5;
      color: #666;
      padding: 20px 25px;
      border-top: 1px solid #eee;
      text-align: center;
    }

    .help a {
      color: {{ $accentColor }};
      text-decoration: none;
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
      <h1>Verify Your Email Address</h1>
      <p>Hello {{ $user->name ?? 'there' }},</p>
      <p>Thank you for signing up with <strong>{{ $brandName }}</strong>!
        Please confirm your email address to activate your account.</p>

      <a href="{{ $verifyUrl }}" class="btn" target="_blank">Verify Email</a>

      <p style="font-size:13px; color:#777; margin-top:20px;">
        If the button doesn’t work, copy this link into your browser:<br>
        <a href="{{ $verifyUrl }}" target="_blank" style="color:{{ $accentColor }};">{{ $verifyUrl }}</a>
      </p>
    </div>

    <!-- Help Section -->
    <div class="help">
      <p>Didn’t create an account? You can safely ignore this email.</p>
      <p>Need help? Contact us at <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a></p>
    </div>

    <!-- Footer -->
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
