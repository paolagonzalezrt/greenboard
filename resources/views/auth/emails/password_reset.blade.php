<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('passwords.email_subject') }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f6f6f6;
            color: #212121;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            max-width: 600px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .card {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
        }
        .header {
            padding: 24px 48px 12px 48px;
        }
        .logo-text {
            font-size: 24px;
            font-weight: 700;
            color: #212121;
            letter-spacing: -0.3px;
            text-align: center;
        }
        
        .body {
            padding: 16px 48px 48px 48px;
        }
        .greeting {
            font-size: 20px;
            font-weight: 700;
            color: #212121;
            margin-bottom: 24px;
            letter-spacing: -0.2px;
        }
        .text {
            font-size: 15px;
            line-height: 1.6;
            color: #585858;
            margin-bottom: 12px;
        }
        .button-wrapper {
            margin: 36px 0;
            text-align: center;
        }
        .button {
            display: inline-block;
            background: #13ec5b;
            color: #212121 !important;
            font-size: 14px;
            font-weight: 700;
            padding: 14px 32px;
            border-radius: 50px;
            text-decoration: none;
            letter-spacing: 0.2px;
        }
        .note-container {
            background: #f6f6f6;
            border-left: 3px solid #13ec5b;
            padding: 16px 20px;
            /* margin-bottom: 16px; */
            border-radius: 0 8px 8px 0;
        }
        .note-text {
            font-size: 13px;
            line-height: 1.5;
            color: #585858;
            /* margin-bottom: 8px; */
        }
        .note-text:last-child {
            margin-bottom: 0;
        }
        .url-fallback {
            font-size: 12px;
            color: #585858;
            line-height: 1.5;
            border-top: 1px solid #f6f6f6;
            padding-top: 24px;
        }
        .url-link {
            color: #13ec5b !important;
            text-decoration: none;
            word-break: break-all;
            display: block;
            margin-top: 8px;
            font-weight: 600;
            filter: brightness(0.85);
        }
        .footer {
            padding: 32px 48px;
            text-align: center;
        }
        .footer-text {
            font-size: 12px;
            color: #585858;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <div class="logo-text">GreenBoard</div>
            </div>
            
            <div class="body">
                <h1 class="greeting">{{ __('passwords.email_greeting', ['name' => $userName]) }}</h1>
                
                <p class="text">{{ __('passwords.email_intro') }}</p>
                
                <p class="text">{{ __('passwords.email_action', ['count' => $expireMinutes]) }}</p>
                
                <div class="button-wrapper">
                    <a href="{{ $resetUrl }}" class="button">{{ __('passwords.email_button') }}</a>
                </div>
                
                <div class="note-container">
                    <p class="note-text">{{ __('passwords.email_expire', ['count' => $expireMinutes]) }}</p>
                    <p class="note-text">{{ __('passwords.email_ignore') }}</p>
                </div>
                
                <div class="url-fallback">
                    <p>{{ __('passwords.email_fallback_text') }}</p>
                    <a href="{{ $resetUrl }}" class="url-link">{{ $resetUrl }}</a>
                </div>
            </div>
            
            <div class="footer">
                <p class="footer-text">
                    <strong>GreenBoard Team</strong><br>
                    {{ __('passwords.email_auto_notice') }}
                </p>
            </div>
        </div>
    </div>
</body>
</html>
