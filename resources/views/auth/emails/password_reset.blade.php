<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('passwords.email_subject') }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f1f5f9; color: #1e293b; }
        .wrapper { max-width: 600px; margin: 40px auto; padding: 20px; }
        .card { background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
        .header { background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%); padding: 40px 40px 32px; text-align: center; }
        .logo { display: inline-flex; align-items: center; gap: 10px; margin-bottom: 0; }
        .logo-icon { font-size: 32px; color: #166534; }
        .logo-text { font-size: 26px; font-weight: 900; color: #14532d; letter-spacing: -0.5px; }
        .body { padding: 40px; }
        .greeting { font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 16px; }
        .text { font-size: 15px; line-height: 1.7; color: #475569; margin-bottom: 16px; }
        .button-wrapper { text-align: center; margin: 32px 0; }
        .button { display: inline-block; background: #22c55e; color: #14532d; font-size: 15px; font-weight: 800; padding: 14px 36px; border-radius: 12px; text-decoration: none; letter-spacing: 0.2px; }
        .divider { border: none; border-top: 1px solid #e2e8f0; margin: 32px 0; }
        .expire-note { font-size: 13px; color: #94a3b8; text-align: center; margin-bottom: 8px; }
        .ignore-note { font-size: 13px; color: #94a3b8; text-align: center; }
        .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 24px 40px; text-align: center; }
        .footer-text { font-size: 12px; color: #94a3b8; line-height: 1.6; }
        .url-fallback { font-size: 12px; color: #94a3b8; word-break: break-all; margin-top: 16px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <!-- Header -->
            <div class="header">
                <div class="logo">
                    <span class="logo-text">🌿 GreenBoard</span>
                </div>
            </div>

            <!-- Body -->
            <div class="body">
                <p class="greeting">{{ __('passwords.email_greeting', ['name' => $userName]) }}</p>

                <p class="text">{{ __('passwords.email_intro') }}</p>

                <p class="text">{{ __('passwords.email_action', ['count' => $expireMinutes]) }}</p>

                <div class="button-wrapper">
                    <a href="{{ $resetUrl }}" class="button">
                        {{ __('passwords.email_button') }}
                    </a>
                </div>

                <hr class="divider">

                <p class="expire-note">
                    ⏱ {{ __('passwords.email_expire', ['count' => $expireMinutes]) }}
                </p>

                <p class="ignore-note">
                    {{ __('passwords.email_ignore') }}
                </p>

                <div class="url-fallback">
                    <p style="color:#94a3b8; font-size:12px; margin-bottom:6px;">Si el botón no funciona, copia y pega este enlace en tu navegador:</p>
                    <a href="{{ $resetUrl }}" style="color:#22c55e;">{{ $resetUrl }}</a>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p class="footer-text">
                    GreenBoard — Comunidad de consejos sostenibles<br>
                    Este es un correo automático, por favor no respondas a este mensaje.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
