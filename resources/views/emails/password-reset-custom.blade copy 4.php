@php
  $brandName = $brandName ?? config('app.name');
  $brandColor = $brandColor ?? '#257e89'; // bright blue CTA
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
      font-family: Arial, Helvetica, sans-serif;
      background: #fafafa;
      color: {{ $textColor }};
    }

    .container {
      max-width: 480px;
      margin: 30px auto;
      background: #fff;
      border: 1px solid #eaeaea;
      border-radius: 8px;
    }

    .header {
      padding: 20px;
      border-bottom: 1px solid #eee;
      text-align: center;
    }

    .content {
      padding: 25px 20px;
      font-size: 15px;
      line-height: 1.5;
    }

    .btn {
      display: inline-block;
      background: {{ $brandColor }};
      color: #fff !important;
      padding: 12px 20px;
      border: 1px solid {{ $brandColor }};
      border-radius: 6px;
      font-weight: 600;
      font-size: 15px;
      text-decoration: none;
    }

    .footer {
      font-size: 12px;
      color: #999;
      padding: 20px;
      border-top: 1px solid #eee;
      text-align: center;
    }

    a {
      color: {{ $brandColor }};
      text-decoration: none;
    }
  </style>
</head>

<body>

  <div class="container">
    <!-- Header -->
    <div class="header">
      <a href="{{ config('app.url') }}" target="_blank">
        <img src="{{ $logoUrl }}" alt="{{ $brandName }}" width="120" style="border:0;">
      </a>
    </div>

    <!-- Body -->
    <div class="content">
      <p>Hi {{ $user->name ?? 'there' }},</p>
      <p>We noticed you requested to reset your <strong>{{ $brandName }}</strong> password. Tap the button below to
        set a new one.</p>

      <p style="margin:25px 0; text-align:center;">
        <a href="{{ $resetUrl }}" class="btn" target="_blank">Reset Password</a>
      </p>

      <p>If you didn’t request this, you can safely ignore this email.</p>
      <div style="">
        <p style="font-size:13px; color:#777; margin-top:25px;">
          Or copy this link into your browser:<br>
          <a href="{{ $resetUrl }}" target="_blank">{{ $resetUrl }}</a>
        </p>
      </div>
    </div>

    <!-- Footer -->
    <div class="footer">
      <p>© {{ date('Y') }} {{ $brandName }}. Need help? Contact us at <a
          href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>.</p>
    </div>
  </div>

</body>

</html>
