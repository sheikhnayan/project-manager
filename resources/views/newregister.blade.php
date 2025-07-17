<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to EasyPeasly!</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; margin: 0; padding: 0; }
        .container { background: #fff; max-width: 500px; margin: 40px auto; border-radius: 8px; box-shadow: 0 2px 8px #e2e8f0; padding: 32px; }
        .btn { display: inline-block; background: #111827; color: #fff; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 24px; }
        .logo { margin-bottom: 24px; }
        .footer { color: #6b7280; font-size: 13px; margin-top: 32px; }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://easypeasly.com/logo.png" alt="EasyPeasly Logo" class="logo" width="120">
        <h2>Welcome to EasyPeasly!</h2>
        <p>Hello {{ $user->name ?? 'User' }},</p>
        <p>
            Your account has been created on EasyPeasly.<br>
            To get started, please set your password by clicking the button below:
        </p>
        <a href="{{ $setPasswordUrl }}" class="btn">Set Your Password</a>
        <p style="margin-top: 24px;">
            If you did not request this account, please ignore this email.
        </p>
        <div class="footer">
            Sent by <a href="mailto:support@easypeasly.com">support@easypeasly.com</a><br>
            &copy; {{ date('Y') }} EasyPeasly. All rights reserved.
        </div>
    </div>
</body>
</html>
