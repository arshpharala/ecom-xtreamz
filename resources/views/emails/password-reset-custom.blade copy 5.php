@php
  $brandName = $brandName ?? config('app.name');
  $brandColor = $brandColor ?? '#257e89'; // main button color
  $textColor = $textColor ?? '#333333';
  $logoUrl = $logoUrl ?? asset('assets/images/logo.png');
  $supportEmail = $supportEmail ?? 'inquest@xtremez.xyz';
@endphp
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Password Reset</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      margin: 0;
      padding: 0;
      background: #f4f6f9;
      font-family: Arial, Helvetica, sans-serif;
      color: {{ $textColor }};
    }

    .container {
      max-width: 600px;
      margin: 30px auto;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      overflow: hidden;
    }

    .header {
      text-align: center;
      padding: 25px 20px 10px;
    }

    .header img {
      max-width: 160px;
    }

    .illustration {
      text-align: center;
      padding: 15px;
    }

    .content {
      padding: 20px 30px;
      text-align: center;
    }

    .content h2 {
      margin: 0 0 15px;
      font-size: 22px;
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
      background: {{ $brandColor }};
      color: #fff !important;
      padding: 14px 28px;
      font-weight: 600;
      font-size: 15px;
      border-radius: 6px;
      text-decoration: none;
      margin: 20px 0;
    }

    .help {
      font-size: 13px;
      line-height: 1.5;
      color: #666;
      text-align: center;
      padding: 20px 30px;
    }

    .help a {
      color: {{ $brandColor }};
      text-decoration: none;
    }

    .footer {
      background: #fafafa;
      padding: 20px;
      text-align: center;
      font-size: 12px;
      color: #999;
    }

    .footer a {
      margin: 0 8px;
      color: {{ $brandColor }};
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

    <!-- Illustration -->
    <div class="illustration">
      <img src="https://img.icons8.com/color/96/000000/password.png" width="100" alt="Password Reset">
    </div>

    <!-- Content -->
    <div class="content">
      <h2>Reset your password</h2>
      <p>We received a request to reset the password for your <strong>{{ $brandName }}</strong> account.<br>
        Click the button below to create a new password.</p>

      <a href="{{ $resetUrl }}" class="btn" target="_blank">Reset my password</a>

      <p style="font-size:13px; color:#777; margin-top:20px;">
        If the button doesn’t work, copy this link:<br>
        <a href="{{ $resetUrl }}" style="color:{{ $brandColor }};">{{ $resetUrl }}</a>
      </p>
    </div>

    <!-- Help Section -->
    <div class="help">
      <p><strong>Questions?</strong><br>
        Reply to this email or contact us at <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>.</p>
      <p>If you didn’t request this reset, please ignore this email or <br>
        <a href="{{ config('app.url') }}/contact">get in touch with our support team</a>.
      </p>
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
