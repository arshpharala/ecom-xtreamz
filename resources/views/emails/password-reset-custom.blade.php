@php
  $brandName     = $brandName     ?? config('app.name');
  $brandColor    = $brandColor    ?? '#0D47A1';        // primary
  $accentColor   = $accentColor   ?? '#1565C0';        // header gradient end
  $textColor     = $textColor     ?? '#22324d';
  $logoUrl       = $logoUrl       ?? asset('assets/images/logo.png');
  $supportEmail  = $supportEmail  ?? (config('mail.from.address') ?: 'support@'.parse_url(config('app.url'), PHP_URL_HOST));
@endphp
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Reset your password</title>
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
<body style="margin:0; padding:0; background:#f4f7fb;">
  <div class="preheader">Reset your {{ $brandName }} password — link inside.</div>

  <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background:#f4f7fb;">
    <tr>
      <td align="center" style="padding:24px;">
        <table role="presentation" class="container" width="600" cellpadding="0" cellspacing="0" style="width:600px; max-width:600px; background:#ffffff; border:1px solid #e6eaf1; border-radius:14px; overflow:hidden;">
          <!-- Header -->
          <tr>
            <td style="background:linear-gradient(135deg, {{ $brandColor }} 0%, {{ $accentColor }} 100%); padding:20px 24px; text-align:center;">
              <a href="{{ config('app.url') }}" target="_blank" style="text-decoration:none;">
                <img src="{{ $logoUrl }}" alt="{{ $brandName }} Logo" width="160" style="display:block; margin:0 auto; border:0; max-width:160px;">
              </a>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td class="px-24 py-32" style="padding:32px 24px; color:{{ $textColor }}; font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica,Arial,sans-serif; font-size:15px; line-height:1.58;">
              <h1 class="h1" style="margin:0 0 12px; font-size:24px; line-height:30px; color:#0b1b33; font-weight:700;">Reset your password</h1>

              <p style="margin:0 0 12px;">Hello {{ $user->name ?? 'there' }},</p>
              <p style="margin:0 0 16px;">
                We received a request to reset the password for your <strong>{{ $brandName }}</strong> account.
                Click the button below to choose a new password.
              </p>

              <!-- Button (bulletproof) -->
              <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin:20px 0;">
                <tr>
                  <td align="center" bgcolor="{{ $brandColor }}" style="border-radius:10px;">
                    <a href="{{ $resetUrl }}"
                       style="display:inline-block; padding:12px 22px; font-weight:600; text-decoration:none; color:#ffffff; background:{{ $brandColor }}; border:1px solid {{ $brandColor }}; border-radius:10px;"
                       target="_blank" rel="noopener">
                      Reset Password
                    </a>
                  </td>
                </tr>
              </table>

              <!-- Fallback link -->
              <p style="margin:16px 0 0;">
                If the button doesn’t work, copy and paste this link into your browser:
              </p>
              <p style="margin:8px 0 16px; word-break:break-all;">
                <a href="{{ $resetUrl }}" target="_blank" style="color:{{ $brandColor }}; text-decoration:underline;">{{ $resetUrl }}</a>
              </p>

              <hr style="border:0; border-top:1px solid #e6eaf1; margin:18px 0;">

              <p style="margin:0 0 6px; font-size:13px; color:#4a5b78;">
                Didn’t request this? You can safely ignore this email—your password won’t change unless you use the link above.
              </p>

              <p style="margin:0; font-size:13px; color:#4a5b78;">
                Need help? Contact us at
                <a href="mailto:{{ $supportEmail }}" style="color:{{ $brandColor }}; text-decoration:underline;">{{ $supportEmail }}</a>.
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background:#ffffff; padding:0 24px 24px;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #eef2f7;">
                <tr>
                  <td style="padding-top:16px; text-align:center; font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica,Arial,sans-serif; font-size:12px; color:#71829e;">
                    <div style="margin-bottom:8px;">
                      © {{ date('Y') }} {{ $brandName }} ·
                      <a href="{{ config('app.url') }}" target="_blank" style="color:#71829e; text-decoration:underline;">{{ parse_url(config('app.url'), PHP_URL_HOST) }}</a>
                    </div>
                    @isset($unsubscribeUrl)
                      <div>
                        <a href="{{ $unsubscribeUrl }}" target="_blank" style="color:#71829e; text-decoration:underline;">
                          Unsubscribe / Manage email preferences
                        </a>
                      </div>
                    @endisset
                  </td>
                </tr>
              </table>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
