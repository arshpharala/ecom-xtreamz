@php
  $brandName     = $brandName     ?? config('app.name');
  $brandColor    = $brandColor    ?? '#0056b3';   // header/footer color
  $textColor     = $textColor     ?? '#333333';
  $logoUrl       = $logoUrl       ?? asset('assets/images/logo-white.png');
  $supportEmail  = $supportEmail  ?? 'support@xtremez.xyz';
@endphp
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Password Reset</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body { margin:0; padding:0; background:#f7f9fb; font-family:Arial,Helvetica,sans-serif; }
    .container { max-width:600px; margin:0 auto; background:#ffffff; border-radius:6px; overflow:hidden; }
    .btn { display:inline-block; padding:12px 26px; background:#ffffff; border:2px solid {{ $brandColor }};
           color:{{ $brandColor }}; font-weight:600; border-radius:6px; text-decoration:none; }
    .btn:hover { background:{{ $brandColor }}; color:#fff; }
    .footer a { color:#999; text-decoration:none; margin:0 6px; }
  </style>
</head>
<body>

  <!-- Header -->
  <table width="100%" bgcolor="{{ $brandColor }}" style="padding:20px 0;">
    <tr>
      <td align="center">
        <a href="{{ config('app.url') }}" target="_blank">
          <img src="{{ $logoUrl }}" alt="{{ $brandName }}" width="160" style="border:0; display:block;">
        </a>
      </td>
    </tr>
  </table>

  <!-- Main container -->
  <div class="container" style="padding:30px 20px; text-align:center;">

    <!-- Illustration -->
    <div style="margin-bottom:20px;">
      <img src="https://img.icons8.com/color/96/000000/lock-2.png" alt="Reset Password" width="96">
    </div>

    <!-- Title -->
    <h2 style="margin:0 0 10px; color:#111; font-size:22px;">FORGOT YOUR PASSWORD?</h2>

    <!-- Text -->
    <p style="color:{{ $textColor }}; font-size:15px; margin:0 0 20px;">
      Hi {{ $user->name ?? 'there' }},<br>
      We received a request to reset your <strong>{{ $brandName }}</strong> account password.
    </p>
    <p style="color:{{ $textColor }}; font-size:14px; margin:0 0 25px;">
      If you didn’t request this, just ignore this email. Otherwise, click the button below:
    </p>

    <!-- Button -->
    <p>
      <a href="{{ $resetUrl }}" class="btn" target="_blank">RESET PASSWORD</a>
    </p>

    <!-- Fallback -->
    <p style="font-size:12px; color:#777; margin-top:20px; word-break:break-all;">
      Or copy and paste this link:<br>
      <a href="{{ $resetUrl }}" style="color:{{ $brandColor }};">{{ $resetUrl }}</a>
    </p>
  </div>

  <!-- Footer -->
  <table width="100%" bgcolor="{{ $brandColor }}" style="padding:20px 0; margin-top:20px;">
    <tr>
      <td align="center" style="color:#fff; font-size:13px;">
        <p style="margin:0 0 10px;">Follow us:</p>
        <p>
          <a href="#" style="color:#fff; text-decoration:none;">Facebook</a> |
          <a href="#" style="color:#fff; text-decoration:none;">Twitter</a> |
          <a href="#" style="color:#fff; text-decoration:none;">Instagram</a> |
          <a href="#" style="color:#fff; text-decoration:none;">LinkedIn</a>
        </p>
        <p style="margin:10px 0 0;">© {{ date('Y') }} {{ $brandName }} · <a href="{{ config('app.url') }}" style="color:#fff; text-decoration:underline;">Website</a></p>
      </td>
    </tr>
  </table>

</body>
</html>
