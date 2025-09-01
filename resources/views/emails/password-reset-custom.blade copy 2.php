@php
  $brandName     = $brandName     ?? config('app.name');
  $brandColor    = $brandColor    ?? '#0066cc';        // primary blue
  $accentColor   = $accentColor   ?? '#f5f9ff';        // light background
  $textColor     = $textColor     ?? '#333333';
  $logoUrl       = $logoUrl       ?? asset('assets/images/logo.png');
  $supportEmail  = $supportEmail  ?? 'support@xtremez.xyz';
@endphp
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Password Reset Request</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    @media (max-width: 640px) {
      .container { width: 100% !important; }
      .px-24 { padding-left: 16px !important; padding-right: 16px !important; }
      .py-32 { padding-top: 20px !important; padding-bottom: 20px !important; }
      .h1 { font-size: 22px !important; line-height: 28px !important; }
    }
    .preheader { display:none!important; visibility:hidden; opacity:0; color:transparent; height:0; width:0; overflow:hidden; }
  </style>
</head>
<body style="margin:0; padding:0; background:#eef2f7;">

  <div class="preheader">Reset your {{ $brandName }} account password.</div>

  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#eef2f7;">
    <tr>
      <td align="center" style="padding:30px 15px;">

        <!-- Card -->
        <table role="presentation" width="600" class="container" cellspacing="0" cellpadding="0" border="0" style="max-width:600px; background:#ffffff; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.05); overflow:hidden;">

          <!-- Logo Header -->
          <tr>
            <td align="center" style="padding:20px; background:{{ $accentColor }};">
              <a href="{{ config('app.url') }}" target="_blank">
                <img src="{{ $logoUrl }}" alt="{{ $brandName }} Logo" width="140" style="display:block; border:0;">
              </a>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td class="px-24 py-32" style="padding:32px 24px; font-family:Segoe UI, Roboto, Helvetica, Arial, sans-serif; color:{{ $textColor }}; font-size:15px; line-height:1.6;">

              <h1 class="h1" style="margin:0 0 15px; font-size:23px; color:#111111; font-weight:600; text-align:center;">
                Reset Your Password
              </h1>

              <p style="margin:0 0 15px; text-align:center;">
                Hi {{ $user->name ?? 'there' }}, we received a request to reset the password for your <strong>{{ $brandName }}</strong> account.
              </p>
              <p style="margin:0 0 25px; text-align:center;">
                Click the button below to set a new password. This link will expire shortly for security reasons.
              </p>

              <!-- Button -->
              <table role="presentation" align="center" style="margin:0 auto 20px;">
                <tr>
                  <td bgcolor="{{ $brandColor }}" style="border-radius:8px;">
                    <a href="{{ $resetUrl }}" target="_blank"
                       style="display:inline-block; padding:14px 28px; font-weight:600; font-size:15px; color:#ffffff; background:{{ $brandColor }}; text-decoration:none; border-radius:8px;">
                      Reset Password
                    </a>
                  </td>
                </tr>
              </table>

              <!-- Fallback link -->
              <p style="font-size:13px; margin:20px 0; text-align:center; color:#555;">
                If the button doesn’t work, copy and paste this link into your browser:
              </p>
              <p style="font-size:13px; word-break:break-all; text-align:center;">
                <a href="{{ $resetUrl }}" target="_blank" style="color:{{ $brandColor }};">{{ $resetUrl }}</a>
              </p>

              <hr style="border:0; border-top:1px solid #eee; margin:30px 0;">

              <p style="margin:0; font-size:13px; color:#666; text-align:center;">
                If you didn’t request this password reset, you can safely ignore this email.
                Need help? Contact us at
                <a href="mailto:{{ $supportEmail }}" style="color:{{ $brandColor }};">{{ $supportEmail }}</a>.
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td align="center" style="background:#fafafa; padding:15px; font-family:Segoe UI, Roboto, Helvetica, Arial, sans-serif; font-size:12px; color:#999;">
              © {{ date('Y') }} {{ $brandName }} ·
              <a href="{{ config('app.url') }}" target="_blank" style="color:#999; text-decoration:none;">{{ parse_url(config('app.url'), PHP_URL_HOST) }}</a>
              @isset($unsubscribeUrl)
                · <a href="{{ $unsubscribeUrl }}" target="_blank" style="color:#999;">Unsubscribe</a>
              @endisset
            </td>
          </tr>
        </table>
        <!-- /Card -->

      </td>
    </tr>
  </table>

</body>
</html>
